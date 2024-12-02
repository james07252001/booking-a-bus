<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DhāπyūTāmāπū</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('https://scontent.fmnl9-5.fna.fbcdn.net/v/t1.15752-9/426606737_419585840611537_5248726057843684061_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeHwy5VcMRCkHyKvtP8SwxFGAklUhE8KoAICSVSETwqgAj3mLoQvj7levQ3z9-kMZfJ6G4K5kQzrRtEB5SAvksQw&_nc_ohc=oS-aUsiboyIQ7kNvgEjJOnb&_nc_ht=scontent.fmnl9-5.fna&_nc_gid=AaxEBLDWc9CKiAxkiyTjagR&oh=03_Q7cD1QFZbUiaRUrLIIJ-jyh0zqMJnnZFOC9FvTtB_6aUa6ggwA&oe=672CA15E');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            overflow: hidden;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            margin-top: 105px;
            padding: 20px;
            background-image: linear-gradient( 68.1deg,  rgba(196,69,69,1) 9.2%, rgba(255,167,73,0.82) 25%, rgba(253,217,82,0.82) 43.4%, rgba(107,225,108,0.82) 58.2%, rgba(107,169,225,0.82) 75.1%, rgba(153,41,243,0.82) 87.3% );
        }
        h1 {
            color: white;
            text-align: center;
            padding: 20px 0;
            margin: 0;
            font-size: 3em;
        }
        .heartbeat {
            display: inline-block;
            animation: heartbeat 1.5s ease-in-out infinite;
        }
        @keyframes heartbeat {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
        }
        .content {
            margin-top: 20px;
            background-image: linear-gradient( 179.7deg,  rgba(249,21,215,1) 1.1%, rgba(22,0,98,1) 99% );;
        }
        .content p {
            color: #ffffff;
            font-size: 2.1em;
            line-height: 1.5;
            padding: 20px;
            border-right: 2px solid white;
            white-space: nowrap;
            overflow: hidden;
            width: 0;
            animation: typing 4s steps(60, end), blink 0.75s step-end infinite;
            animation-iteration-count: infinite;
            animation-delay: 1s;
        }
        @keyframes typing {
            0% {
                width: 0;
            }
            50% {
                width: 100%;
            }
            100% {
                width: 0;
            }
        }
        @keyframes blink {
            50% {
                border-color: transparent;
            }
        }
        .binary {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        .binary span {
            position: absolute;
            top: -100px;
            font-size: 16px;
            font-family: monospace;
            color: rgba(0, 255, 0, 0.7);
            animation: fall linear infinite;
        }
        @keyframes fall {
            from {
                transform: translateY(-100%);
            }
            to {
                transform: translateY(100vh);
            }
        }
    </style>
</head>
<body>
    <div class="binary">
        <span style="left: 10%; animation-duration: 3s;">.˚⊹.🎃₊˚𖦹⋆</span>
        <span style="left: 20%; animation-duration: 4s;">.˚⊹.🎃₊˚𖦹⋆</span>
        <span style="left: 30%; animation-duration: 5s;">.˚⊹.🎃₊˚𖦹⋆</span>
        <span style="left: 40%; animation-duration: 3.5s;">.˚⊹.🎃₊˚𖦹⋆</span>
        <span style="left: 50%; animation-duration: 6s;">.˚⊹.🎃₊˚𖦹⋆</span>
        <span style="left: 60%; animation-duration: 4.2s;">.˚⊹.🎃₊˚𖦹⋆</span>
        <span style="left: 70%; animation-duration: 5.2s;">.˚⊹.🎃₊˚𖦹⋆</span>
        <span style="left: 90%; animation-duration: 5.8s;">.˚⊹.🎃₊˚𖦹⋆</span>
    </div>

    <div class="container">
        <h1>
            <span class="heartbeat">Programmer Who Put This!!</span><br>
            <span class="heartbeat">B</span>
            <span class="heartbeat">a</span>
            <span class="heartbeat">n</span>
            <span class="heartbeat">t</span>
            <span class="heartbeat">y</span>
            <span class="heartbeat">a</span>
            <span class="heartbeat">n</span>
            <span class="heartbeat">B</span>
            <span class="heartbeat">u</span>
            <span class="heartbeat">S</span>
        </h1>
        <div class="content">
            <p> "Today a reader, tomorrow a leader." - Margaret Fuller</p>
        </div>
    </div>
</body>
</html>

