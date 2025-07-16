<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-end; /* Kontainer di bagian bawah */
            align-items: center;
            min-height: 100vh;
            color: #ffffff;
            overflow: hidden;

            /* --- Pengaturan Latar Belakang Foto dan Gradien --- */
            background: 
                url('{{ asset("images/background-403_2.jpg") }}') no-repeat center center, 
                linear-gradient(to bottom right, #1a202c, #2d3748); 
            background-size: cover, cover;
            background-attachment: fixed; 
            /* --- Akhir Pengaturan Latar Belakang Foto dan Gradien --- */
        }

        .forbidden-container {
            text-align: center;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 50px 80px; /* Padding horizontal lebih besar untuk membuat kontainer lebih panjang */
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            max-width: 95%; /* Kontainer akan memenuhi hampir seluruh lebar layar */
            width: 100%; /* Pastikan width 100% untuk memanfaatkan max-width */
            margin-bottom: 5vh; /* Menambahkan margin bawah agar tidak terlalu mepet */
            position: relative;
            z-index: 1;
        }

        .forbidden-container h1 {
            font-size: 9em;
            margin: 0;
            font-weight: 700;
            letter-spacing: 7px;
            text-shadow: 6px 6px 10px rgba(0, 0, 0, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .text-403 {
            color: #dc3545;
        }

        .text-bidden {
            color: transparent;
            background: linear-gradient(
                to right, 
                #0a3161 0%, #0a3161 30%,
                #bd3d44 30%, #bd3d44 40%,
                #ffffff 40%, #ffffff 50%,
                #bd3d44 50%, #bd3d44 60%,
                #ffffff 60%, #ffffff 70%,
                #bd3d44 70%, #bd3d44 80%,
                #ffffff 80%, #ffffff 90%,
                #bd3d44 90%, #bd3d44 100%
            );
            -webkit-background-clip: text;
            background-clip: text;
            font-size: 1em;
            letter-spacing: 0.1em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .forbidden-container h1 {
                font-size: 7em;
                letter-spacing: 5px;
                gap: 10px;
            }
            .forbidden-container {
                padding: 40px 60px;
            }
        }

        @media (max-width: 768px) {
            .forbidden-container h1 {
                font-size: 5.5em;
                letter-spacing: 4px;
                gap: 8px;
            }
            .forbidden-container {
                padding: 30px 40px;
            }
        }

        @media (max-width: 576px) {
            .forbidden-container h1 {
                font-size: 3.5em;
                letter-spacing: 2px;
                flex-direction: column;
                gap: 0px;
            }
            .forbidden-container {
                padding: 25px 30px;
            }
            .forbidden-container h1 .text-bidden {
                 font-size: 0.8em;
            }
        }
    </style>
</head>
<body>
    <div class="forbidden-container">
        <h1>
            <span class="text-403">403</span>
            <br>
            <span class="text-bidden">For-Bidden</span>
        </h1>
    </div>

    <audio id="backgroundAudio" autoplay loop>
        <source src="{{ asset('audio/Eagle Sound Effect.mp3') }}" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const audio = document.getElementById('backgroundAudio');
            // Coba putar audio. Ini mungkin diblokir oleh browser.
            audio.play().catch(error => {
                console.log('Autoplay was prevented:', error);
                // Opsi: Tampilkan tombol "Play Sound" jika autoplay diblokir
                // Misalnya:
                // const playButton = document.createElement('button');
                // playButton.textContent = 'Play Sound';
                // playButton.addEventListener('click', () => audio.play());
                // document.body.appendChild(playButton);
            });
        });
    </script>
</body>
</html>