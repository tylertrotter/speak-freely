
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Speak Feely</title>
        <!-- <meta name="description" content=""> -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- <link rel="apple-touch-icon" href="apple-touch-icon.png"> -->
        <link rel="stylesheet" href="css/main.css">
        <!-- <script src="js/vendor/modernizr-2.8.3.min.js"></script> -->
    </head>
    <?php
        if (isset($_GET['r'])) {
            echo '<body data-rounds="' . $_GET['r'] . '" data-teams="The Lexiconquistadors, The Word Warriors" data-score="' . $_GET['s'] . '" data-current-team="' . $_GET['ct'] . '">';
        }else{
            echo '<body data-rounds="0" data-teams="The Lexiconquistadors, The Word Warriors" data-score="0,0" data-current-team="1">';
        }
    ?>
    
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an outdated browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <section id="start">
        <button class="start-round">Start Game</button>
         <code>http://localhost/word-game?r=2&amp;s=10,20&amp;ct=0</code>
    </section>
    
    <section id="confirm">
        <div class="current-team">Team A</div>
        <h1>Confirm Your Results</h1>
        <ol></ol>
        <div>
            Total: <span id="round-points">0</span>
        </div>
        <button id="confirm-results">Looks Good</button>
    </section>
    
    <section id="scoreboard">
        <div class="score-container">
            <div class="team-0">
                <span class="team-0-name">Team A</span>
                <span class="team-0-score">0</span>
                <ol class="rounds">
                    <li></li><li></li><li></li><li></li><li></li><li></li>
                </ol>
            </div>
            <div class="team-1">
                <span class="team-1-name">Team B</span>
                <span class="team-1-score">0</span>
                <ol class="rounds">
                    <li></li><li></li><li></li><li></li><li></li><li></li>
                </ol>
            </div>
        </div>
        <button class="start-round">Start Next Round</button>
        <div><span class="current-team">Team</span> are up next.</div>
        
        <hr>
        
        Save or share the current game.
        <code>http://localhost/word-game?r=2&amp;s=10,20&amp;ct=0</code>
        <input type="text" value="">
    </section>
    
    <section id="gameover">
        <h1>Game Over</h1>
        <span id="winning-team">winning team</span> won!
    </section>
    
    <section id="in-progress">
        <div class="current-team">Team A</div>
        <div id="timer">
            <div></div>
        </div>
        
        <section id="controls">
            <div id="pass">
                <button>X</button>
                <div class="points-at-stake">-3</div>
            </div>

            <div id="correct">
                <button>&gt;</button>
                <div class="points-at-stake">6</div>
            </div>
        </section>
        
        <main id="word"></main>

        <section id="restriction">
            Forbidden: <span></span>
        </section>

        
    </section>
    
    <div id="score">
        <button id="show-score">Score V</button>
        <div class="score-container">
            <div class="team-0">
                <div class="team-0-name">Team Name</div>
                <div class="score team-0-score">0</div>
                <ol class="rounds">
                    <li></li><li></li><li></li><li></li><li></li><li></li>
                </ol>
            </div>
             <div class="team-1">
                <div class="team-1-name">Team Name</div>
                <div class="score team-1-score">0</div>
                <ol class="rounds">
                    <li></li><li></li><li></li><li></li><li></li><li></li>
                </ol>
            </div>
        </div>
    </div>
   <!-- <script src="js/zepto.js"></script>-->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <!--     <script>window.jQuery || document.write('<script src="js/vendor/jquery-2.1.3.min.js"><\/script>')</script>-->
    <script>
        function startRound(){
            $('body').find('>section').hide();
            getWord();
            $('#in-progress').show();
            $('#confirm').find('ol').html('');
            $('#timer').find('div').css('width', 0);
            timer(.03 * 60 * 1000);
        }
        function switchCurrentTeam(){
            var teams = $('body').attr('data-teams').split(',');
            var currentTeam = $('body').attr('data-current-team');
            var newCurrentTeam = Math.abs( currentTeam - 1 );
            $('body').attr('data-current-team', newCurrentTeam);
            $('.current-team').text(teams[newCurrentTeam]);
        }
        function getWord(){
            var wordList = JSON.parse(localStorage.getItem('wordsString')).words;
            var points;
            var randomWord = wordList[Math.floor(Math.random()*wordList.length)];
            $('#word').text(randomWord.w);
            if( randomWord.d > 500 ){
                points = 1;   
            }else if( randomWord.d > 400 ){
                points = 2;   
            }else if( randomWord.d > 300 ){
                points = 3;   
            }else if( randomWord.d > 200 ){
                points = 4;   
            }else if( randomWord.d > 100 ){
                points = 5;   
            }else if( randomWord.d > 75 ){
                points = 6;   
            }else if( randomWord.d > 50 ){
                points = 7;   
            }else if( randomWord.d > 25 ){
                points = 8;   
            }else if( randomWord.d > 10 ){
                points = 9;   
            }else{
                points = 10;   
            }
            $('#pass').find('.points-at-stake').text(points-11);
            $('#correct').find('.points-at-stake').text(points);
        }
        function timer(seconds){
            $('#timer').find('div').animate({width: '100%'}, seconds, 'linear', function(){
                endRound();    
            });
        }
        function endRound(){
            $('#confirm').find('ol').append('<li>' + $('#word').text() + '<button data-confirm="pass" data-points="' + $('#pass').find('.points-at-stake').text() + '"></button><button data-confirm="correct" data-points="' + $('#correct').find('.points-at-stake').text() + '"></button></li>');
            
            calcRoundTotal();
            
            $('body').find('>section').hide();
            $('#confirm').show();
        }
        function goToScoreBoard(){
            var points = $('#round-points').text() * 1;
            var teamClassName = '.team-' + $('body').attr('data-current-team') + '-score';
            switchCurrentTeam();
            calcScore(teamClassName,points);
            incrementRound();
            $('body').find('>section').hide();
            if( $('body').attr('data-rounds') === '12' ){
                if($('#score').find('.team-0-score').text()*1 > $('#score').find('.team-1-score').text()*1){
                    $('#winning-team').text($('#score').find('.team-0-name').text());        
                }else if( $('#score').find('.team-0-score').text()*1 < $('#score').find('.team-1-score').text()*1 ){
                    $('#winning-team').text($('#score').find('.team-1-name').text());
                }
               $('#gameover').show(); 
            }else{
                $('#scoreboard').show();
            }
        }
        function calcRoundTotal(){
            var points = 0;
            $('#confirm').find('li').each(function(){
                if( $(this).hasClass('pass') || $(this).hasClass('correct') ){
                    var passOrCorrect = $(this).attr('class');
                    points = points + ($(this).find('[data-confirm=' + passOrCorrect + ']').attr('data-points')*1);
                }
                $('#round-points').text(points);  
            });
            
        }
        function calcScore(teamClassName, points){
            var currentPoints = $('#scoreboard').find(teamClassName).text()*1;
            var newTotal = currentPoints + points;
            $(teamClassName).text(newTotal);
        }
        function incrementRound(){
            var roundNum = $('body').attr('data-rounds')*1;   
            roundNum++;
            $('.score-container').each(function(){
               $(this).find('.team-0').find('.rounds').find('li').eq( Math.ceil(roundNum/2)-1 ).addClass('completed');
                if( Math.floor(roundNum/2)-1 >= 0){
                    $(this).find('.team-1').find('.rounds').find('li').eq( Math.floor(roundNum/2)-1 ).addClass('completed');
                } 
            });
            
            $('body').attr('data-rounds', roundNum);
        }
        $(document).on('click', '.start-round', function(){
             startRound();   
        });
        
        wordNumber = 0;
        $('#in-progress').on('click', '#controls > div', function(){ 
            wordNumber++;
            var checked = [];
            var passPoints = $('#pass').find('.points-at-stake').text();
            var correctPoints = $('#correct').find('.points-at-stake').text();
            var tallyHtml;
 
           $('#confirm').find('ol').append('<li class="' + $(this).attr('id') + '">' + $('#word').text() + '<button data-confirm="pass" data-points="' + $('#pass').find('.points-at-stake').text() + '"></button><button data-confirm="correct" data-points="' + $('#correct').find('.points-at-stake').text() + '"></button></li>');
            
            getWord();
            
        });
  
        $('#confirm').on('click', '#confirm-results', function(){
             goToScoreBoard();   
        });
        
        $('#score').on('click', '#show-score', function(){  
            $('#score').toggleClass('shown');
        });
        
        $('#confirm').find('ol').on('click', 'button', function(){
            var passOrCorrect = $(this).attr('data-confirm');
            var isChecked = $(this).parent().hasClass( passOrCorrect );
            if( isChecked ){
                $(this).parent().removeClass();
            }else{
                $(this).parent().removeClass().addClass(passOrCorrect);
            }
                
            calcRoundTotal();
        });
        
        $(document).ready(function(){
            $.getJSON('wordlist.json', function(data){
                if(!localStorage.getItem('wordsString')){
                    localStorage.setItem('wordsString', JSON.stringify(data));
                }
            });  
            var teams = $('body').attr('data-teams').split(',');
            var score = $('body').attr('data-score').split(',');
            var rounds = $('body').attr('data-rounds');
            
            $('.team-0-name').text(teams[0]);
            $('.team-1-name').text(teams[1]);
            
            $('.team-0-score').text(score[0]);
            $('.team-1-score').text(score[1]);
            
            
            switchCurrentTeam();
            <?php
            if (isset($_GET['r'])) {
                echo "$('#scoreboard').show();"; 
            }else{
                echo "$('#start').show();";
            }
            ?>
        });
        
        /*
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        */

    </script>
</body>

</html>