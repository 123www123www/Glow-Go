<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админ-панель</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="password"], input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        #response {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Вход в админ-панель</h1>
        <form id="admin-login-form">
            <label for="login">Логин:</label>
            <input type="text" id="login" required>

            <label for="password">Пароль:</label>
            <input type="password" id="password" required>

            <button type="submit">Войти</button>
        </form>

        <div id="response"></div>
    </div>

    <script>
        document.getElementById('admin-login-form').onsubmit = function(e) {
            e.preventDefault();
            
            const login = document.getElementById('login').value.trim();
            const password = document.getElementById('password').value.trim();
            const responseDiv = document.getElementById('response');

            // Простая проверка логина и пароля
            if (login === 'admin' && password === 'admin') {
                // Перенаправление на adminpan
                window.location.href = 'http://localhost/Glow%26Go/adminpan/index.html';
            } else {
                // Сообщение об ошибке
                responseDiv.innerText = 'Неверный логин или пароль.';
                responseDiv.style.color = 'red';
            }
        };
    </script>
</body>
</html>
