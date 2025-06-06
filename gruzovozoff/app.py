from flask import Flask, render_template, request, redirect, url_for, session
from flask_sqlalchemy import SQLAlchemy
from werkzeug.security import generate_password_hash, check_password_hash
import re

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///gruzovozoff.db'
app.config['SECRET_KEY'] = 'change-me'
db = SQLAlchemy(app)

# Models
class User(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(80), unique=True, nullable=False)
    password_hash = db.Column(db.String(120), nullable=False)
    full_name = db.Column(db.String(120), nullable=False)
    phone = db.Column(db.String(20), nullable=False)
    email = db.Column(db.String(120), nullable=False)

class Order(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('user.id'))
    datetime = db.Column(db.String(50))
    weight = db.Column(db.String(50))
    size = db.Column(db.String(50))
    cargo_type = db.Column(db.String(100))
    from_addr = db.Column(db.String(200))
    to_addr = db.Column(db.String(200))
    status = db.Column(db.String(20), default='Новая')
    review = db.Column(db.Text)

# Helpers
USER_RE = re.compile(r'^[\u0400-\u04FF]{6,}$')
PHONE_RE = re.compile(r'^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$')
EMAIL_RE = re.compile(r'^[^@]+@[^@]+\.[^@]+$')
NAME_RE = re.compile(r'^[\u0400-\u04FF ]+$')

def logged_in():
    return 'user_id' in session

@app.route('/')
def index():
    if logged_in():
        return redirect(url_for('orders'))
    return redirect(url_for('login'))

@app.route('/register', methods=['GET', 'POST'])
def register():
    error = None
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        full_name = request.form['full_name']
        phone = request.form['phone']
        email = request.form['email']

        if not USER_RE.match(username):
            error = 'Неверный логин'
        elif len(password) < 6:
            error = 'Пароль слишком короткий'
        elif not NAME_RE.match(full_name):
            error = 'Неверное имя'
        elif not PHONE_RE.match(phone):
            error = 'Неверный телефон'
        elif not EMAIL_RE.match(email):
            error = 'Неверный email'
        elif User.query.filter_by(username=username).first():
            error = 'Логин занят'
        else:
            user = User(username=username,
                        password_hash=generate_password_hash(password),
                        full_name=full_name,
                        phone=phone,
                        email=email)
            db.session.add(user)
            db.session.commit()
            return redirect(url_for('login'))
    return render_template('register.html', error=error)

@app.route('/login', methods=['GET', 'POST'])
def login():
    error = None
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        user = User.query.filter_by(username=username).first()
        if user and check_password_hash(user.password_hash, password):
            session['user_id'] = user.id
            session['username'] = user.username
            return redirect(url_for('orders'))
        else:
            error = 'Неверный логин или пароль'
    return render_template('login.html', error=error)

@app.route('/logout')
def logout():
    session.clear()
    return redirect(url_for('login'))

@app.route('/orders')
def orders():
    if not logged_in():
        return redirect(url_for('login'))
    user = User.query.get(session['user_id'])
    user_orders = Order.query.filter_by(user_id=user.id).all()
    return render_template('orders.html', orders=user_orders)


@app.route('/orders/<int:order_id>/review', methods=['POST'])
def add_review(order_id):
    if not logged_in():
        return redirect(url_for('login'))
    order = Order.query.get(order_id)
    if order and order.user_id == session['user_id'] and order.status == 'Выполнено':
        order.review = request.form['review']
        db.session.commit()
    return redirect(url_for('orders'))

@app.route('/create', methods=['GET', 'POST'])
def create():
    if not logged_in():
        return redirect(url_for('login'))
    if request.method == 'POST':
        order = Order(
            user_id=session['user_id'],
            datetime=request.form['datetime'],
            weight=request.form['weight'],
            size=request.form['size'],
            cargo_type=request.form['cargo_type'],
            from_addr=request.form['from_addr'],
            to_addr=request.form['to_addr'],
            status='Новая')
        db.session.add(order)
        db.session.commit()
        return redirect(url_for('orders'))
    return render_template('create.html')

@app.route('/admin', methods=['GET', 'POST'])
def admin_panel():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        if username == 'admin' and password == 'gruzovik2024':
            session['admin'] = True
        else:
            return render_template('admin_login.html', error='Неверные данные')
    if not session.get('admin'):
        return render_template('admin_login.html')
    status_filter = request.args.get('status')
    if status_filter:
        all_orders = Order.query.filter_by(status=status_filter).all()
    else:
        all_orders = Order.query.all()
    return render_template('admin.html', orders=all_orders, status_filter=status_filter)

@app.route('/admin/delete/<int:order_id>', methods=['POST'])
def delete_order(order_id):
    if not session.get('admin'):
        return redirect(url_for('admin_panel'))
    order = Order.query.get(order_id)
    if order:
        db.session.delete(order)
        db.session.commit()
    return redirect(url_for('admin_panel'))


@app.route('/admin/update/<int:order_id>', methods=['POST'])
def update_status(order_id):
    if not session.get('admin'):
        return redirect(url_for('admin_panel'))
    order = Order.query.get(order_id)
    if order:
        order.status = request.form['status']
        db.session.commit()
    return redirect(url_for('admin_panel'))

if __name__ == '__main__':
    with app.app_context():
        db.create_all()
    app.run(debug=True)
