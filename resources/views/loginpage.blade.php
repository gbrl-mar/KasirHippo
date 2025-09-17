<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hippo Coffee - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            overflow: hidden; /* Mencegah scroll pada layout utama */
        }

        .login-container {
            display: flex;
            width: 100%;
            height: 100%;
        }

        /* Panel Informasi (Sebelah Kiri) */
        .info-panel {
            width: 45%;
            background-color: #4B2E2B; /* Warna Coklat Tua */
            color: #F5F3ED;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
            text-align: center;
        }

        .info-content {
            max-width: 400px;
        }

        .info-content .logo {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        .info-content p {
            font-size: 1.1rem;
            line-height: 1.6;
            opacity: 0.9;
        }

        /* Panel Form (Sebelah Kanan) */
        .form-panel {
            width: 55%;
            background-color: #F5F3ED; /* Warna Putih Gading */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
        }

        .login-form {
            width: 100%;
            max-width: 380px;
        }

        .login-form h1 {
            color: #4B2E2B;
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .login-form .subtitle {
            color: #555;
            margin-bottom: 30px;
        }

        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group label {
            display: block;
            color: #333;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .input-group input {
            width: 100%;
            padding: 14px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        .input-group input:focus {
            outline: none;
            border-color: #4B2E2B;
            box-shadow: 0 0 0 3px rgba(75, 46, 43, 0.1);
        }

        .login-button {
            width: 100%;
            padding: 15px;
            background-color: #4B2E2B;
            color: #FFFFFF;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .login-button:hover {
            background-color: #6a4541;
            transform: translateY(-2px);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
        }
        
        .form-footer a {
            color: #4B2E2B;
            text-decoration: none;
            font-weight: 500;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        }

        /* Desain Responsif */
        @media (max-width: 850px) {
            .login-container {
                flex-direction: column;
            }

            .info-panel, .form-panel {
                width: 100%;
            }
            
            .info-panel {
                height: 35vh; /* Mengurangi tinggi panel info di mobile */
                padding: 20px;
            }
            
            .form-panel {
                height: 65vh;
                justify-content: flex-start; /* Form mulai dari atas */
                padding-top: 40px;
            }

            body {
                overflow: auto; /* Izinkan scroll di mobile */
            }
        }
    </style>
</head>
<body>

    <div class="login-container">

        <div class="info-panel">
            <div class="info-content">
                <div class="logo">Hippo Coffee</div>
                <p>Platform terintegrasi untuk mengelola semua kebutuhan bisnis Anda di satu tempat.</p>
            </div>
        </div>

        <div class="form-panel">
            <div class="login-form">
                <h1>Selamat Datang Kembali</h1>
                <p class="subtitle">Silakan masukkan detail akun Anda.</p>

                <form id="loginForm" method="POST">
                    @csrf

                    <div class="input-group">
                        <label for="email">Alamat Email</label>
                        <input type="email" id="email" name="email" placeholder="contoh@email.com" required autofocus>
                    </div>

                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                    </div>

                    <button type="submit" class="login-button">Masuk</button>

                    <div class="form-footer">
                       <a href="#">Lupa Password?</a>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function getCsrfToken() {
            const name = 'XSRF-TOKEN';
            const cookieString = document.cookie;
            const cookies = cookieString.split(';').map(c => c.trim());
            const cookie = cookies.find(c => c.startsWith(name + '='));
            if (cookie) {
                // Decode URI component untuk handle karakter khusus
                return decodeURIComponent(cookie.substring(name.length + 1));
            }
        return null;
}
    </script>
    <script>
    document.getElementById('loginForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if (!email || !password) {
        alert('Silakan isi semua bidang.');
        return;
    }

    try {
        
        await fetch('/sanctum/csrf-cookie', {
            method: 'GET',
            credentials: 'include'
        });

        
        const response = await fetch('/login', {  
            method: 'POST',
            credentials: 'include', 
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json', 
                'X-XSRF-TOKEN': getCsrfToken() 
            },
            body: JSON.stringify({ email, password }),
        });

        console.log("Fetch selesai ✅, status:", response.status);

        const data = await response.json();

        if (data.message === "Login berhasil") {
            
            if (data.role_id == 1) window.location.href = '/dashboardOwner';
            else if (data.role_id == 2) window.location.href = '/dashboardKasir';
            else if (data.role_id == 3) window.location.href = '/dashboardAdmin';
            else window.location.href = '/';
        } else {
            alert('Login gagal: ' + (data.message || 'Cek email/password'));
        }

    } catch (error) {
        console.error("Error fetch ❌:", error);
    }
});

</script>




</body>
</html>