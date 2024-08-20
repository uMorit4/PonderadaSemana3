from flask import Flask, request, render_template, redirect, url_for
import sqlite3

app = Flask(__name__)

def get_db_connection():
    conn = sqlite3.connect('escritorios.db')
    conn.row_factory = sqlite3.Row
    return conn

@app.route('/')
def index():
    return redirect(url_for('index'))

@app.route('/escritorios')
def index():
    conn = get_db_connection()
    escritorios = conn.execute('SELECT * FROM escritorios').fetchall()
    conn.close()
    return render_template('index.html', escritorios=escritorios)

@app.route('/escritorios/add', methods=('GET', 'POST'))
def add_escritorio():
    if request.method == 'POST':
        num_funcionarios = request.form['num_funcionarios']
        renda_mensal = request.form['renda_mensal']
        gerente = request.form['gerente']
        localizacao = request.form['localizacao']

        conn = get_db_connection()
        conn.execute('INSERT INTO escritorios (num_funcionarios, renda_mensal, gerente, localizacao) VALUES (?, ?, ?, ?)',
                     (num_funcionarios, renda_mensal, gerente, localizacao))
        conn.commit()
        conn.close()
        return redirect(url_for('index'))
    
    return render_template('add_escritorio.html')

if __name__ == '__main__':
    app.run(debug=True)
