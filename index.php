<?php
    require_once('Tictactoe.php');

    if(empty($_POST['box'])){
        $gameBoard = [];
    }else{
        $gameBoard = $_POST['box'];
    }

    $Game = new Tictactoe($gameBoard);
    $Game->scanGame();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tic Tac Toe</title>
    <style>
        .gameSection {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            width: 100%;
        }

        h3 {
            margin: 0px;
            margin-top: 25px;
            text-align: center;
            font-size: 50px;
        }

        h5 {
            margin: 0px;
            margin-bottom: 25px;
            text-align: center;
            font-size: 25px;
        }

        p {
            width: 100%;
            text-align: center;
            margin-bottom: 25px;
        }

        span.alert {
            padding: 10px;
            border-radius: 10px;
            text-align: center;
        }

        .alert.is-tie {
            background-color: blue;
            color: white;
        }

        .alert.is-lose {
            background-color: red;
            color: white;
        }

        .alert.is-win {
            background-color: green;
            color: white;
        }

        .container {
            display: grid;
            grid-template-columns: 100px 100px 100px;
            grid-gap: 10px;
            background-color: #fff;
            color: #444;
        }

        .box {
            background-color: #444;
            color: #fff;
            border-radius: 5px;
            padding: 20px;
            min-height: calc(100px - 40px);
        }

        .box > span {
            font-size: 44px;
        }
    </style>
</head>
<body>
    <h3>Tic Tac Toe</h3>
    <h5>by Ario Widiatmoko</h5>
    <p>
        <?php if($Game->status['gameOver']){ ?>
            <?php if($Game->status['winner'] == 'Player'){ ?>
                <span class="alert is-win">Congrats For Winning!</span>
            <?php } else if($Game->status['winner'] == 'AI') { ?>
                <span class="alert is-lose">Ouch, Sorry for Losing. Let's Try Again!</span>
            <?php } else if($Game->status['tie']) { ?>
                <span class="alert is-tie">Wow, It's a Tie!</span>
            <?php } ?>
        <?php } ?>
    </p>
    <div class="gameSection">
        <form id="gameForm" method="post">
            <div class="container">
                <?php
                    for ($i=0; $i < 9; $i++) { 
                        echo $Game->getBox($i);
                    }
                ?>
                <button type="submit">End Move</button>
                <button id="clearGame">Reset Game</button>
            </div>
        </form>
    </div>
    <script>
        var LastMove = '';

        document.getElementById("clearGame").addEventListener("click", function(e){
            e.preventDefault();

            window.location.replace('');
        });

        var openBox = document.getElementsByClassName("is-open");
        for (var i = 0; i < openBox.length; i++) {
            openBox[i].addEventListener("click", function(e){
                if(LastMove != ''){
                    let lastBox = document.getElementById(LastMove).parentElement;
                    lastBox.className = 'box is-open';
                    document.getElementById(LastMove).value = '';
                    
                    let spanX = document.getElementById(LastMove).previousSibling;
                    spanX.parentNode.removeChild(spanX);
                }

                var target = e.target.attributes[1].value;
                var box = e.target.innerHTML;

                e.target.innerHTML = '<span>x</span>' + box;
                e.target.className = 'box is-x';
                document.getElementById(target).value = 'X';
                LastMove = target;
            });
        }
    </script>
</body>
</html>