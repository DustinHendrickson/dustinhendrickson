$(document).ready(function(){
    //Canvas stuff
    var canvas = $("#canvas")[0];
    var ctx = canvas.getContext("2d");
    var w = $("#canvas").width();
    var h = $("#canvas").height();
    
    //Lets save the cell width in a variable for easy control
    var cw = 10;
    var d;
    var food;
    var poison_food = new Array();
    var score = 0;
    var highscore = 0;
    var gamespeed = 0;
    
    //Lets create the snake now
    var snake_array; //an array of cells to make up the snake
    
    function init()
    {
        if(get_difficulty()=="easy") {
            gamespeed = 120;
        }
        if(get_difficulty()=="medium") {
            gamespeed = 60;
        }
        if(get_difficulty()=="hard") {
            gamespeed = 30;
        }
        d = "right"; //default direction
        create_snake();
        create_food(); //Now we can see the food particle
        //finally lets display the score
        score = 0;

        //Lets move the snake now using a timer which will trigger the paint function
        //every 60ms
        if(typeof game_loop != "undefined") clearInterval(game_loop);
        game_loop = setInterval(paint, gamespeed);
    }
    init();
    
    function create_snake()
    {
        var length = 3; //Length of the snake
        snake_array = []; //Empty array to start with
        for(var i = length-1; i>=0; i--)
        {
            //This will create a horizontal snake starting from the top left
            snake_array.push({x: i, y:0});
        }
    }
    
    //Lets create the food now
    function create_food()
    {
        food = {
            x: Math.round(Math.random()*(w-cw)/cw), 
            y: Math.round(Math.random()*(h-cw)/cw), 
        };
        //This will create a cell with x/y between 0-44
        //Because there are 45(450/10) positions accross the rows and columns
    }

    //Lets create the poison food now
    function create_poison_food()
    {
        for(var i = 0; i < score && score > 0; i++) {
            poison_food[i] = {
                x: Math.round(Math.random()*(w-cw)/cw), 
                y: Math.round(Math.random()*(h-cw)/cw), 
            };
        }
        //This will create a cell with x/y between 0-44
        //Because there are 45(450/10) positions accross the rows and columns
    }
    
    //Lets paint the snake now
    function paint()
    {
        //To avoid the snake trail we need to paint the BG on every frame
        //Lets paint the canvas now
        ctx.fillStyle = "darkgrey";
        ctx.fillRect(0, 0, w, h);
        ctx.strokeStyle = "black";
        ctx.strokeRect(0, 0, w, h);
        
        //The movement code for the snake to come here.
        //The logic is simple
        //Pop out the tail cell and place it infront of the head cell
        var nx = snake_array[0].x;
        var ny = snake_array[0].y;
        //These were the position of the head cell.
        //We will increment it to get the new head position
        //Lets add proper direction based movement now
        if(d == "right") nx++;
        else if(d == "left") nx--;
        else if(d == "up") ny--;
        else if(d == "down") ny++;
        
        //Lets add the game over clauses now
        //This will restart the game if the snake hits the wall
        //Lets add the code for body collision
        //Now if the head of the snake bumps into its body, the game will restart
        if(nx == -1 || nx == w/cw || ny == -1 || ny == h/cw || check_collision(nx, ny, snake_array))
        {
            //restart game
            init();
            return;
        }
        
        //Lets write the code to make the snake eat the food
        //The logic is simple
        //If the new head position matches with that of the food,
        //Create a new head instead of moving the tail
        for(var i = 0; i < score && score > 0; i++) {
            if(nx == poison_food[i].x && ny == poison_food[i].y) {
                init();
                return;
            }
        }
        
        if(nx == food.x && ny == food.y)
        {
            var tail = {x: nx, y: ny};
            score++;
            if(score > highscore) {
                highscore = score;
            }
            //Create new food
            if(score>0) {
                create_poison_food();
            }
            create_food();
        }
        else
        {
            var tail = snake_array.pop(); //pops out the last cell
            tail.x = nx; tail.y = ny;
        }
        //The snake can now eat the food.
        
        snake_array.unshift(tail); //puts back the tail as the first cell
        
        for(var i = 0; i < snake_array.length; i++)
        {
            var c = snake_array[i];
            //Lets paint 10px wide cells
            if(score>=20) {
                paint_cell(c.x, c.y, "rainbow");
            } else {
                paint_cell(c.x, c.y, "black");
            }
        }
        
        //Lets paint the food
        paint_cell(food.x, food.y, "green");
        for(var i = 0; i < score && score > 0; i++) {
            paint_cell(poison_food[i].x, poison_food[i].y, "red");
        }
        //Lets paint the score
        write_ui(score);
    }

    function write_ui(score) 
    {
        // Set UI Text Font settings.
        ctx.font = "15px Verdana";

        // Display the Difficulty
        var difficulty_text = "Difficulty: " + get_difficulty();
        ctx.fillStyle = 'red';
        ctx.fillText(difficulty_text, 5, 15);

        // Display the High score in the top right
        var highscore_text = "Highscore: " + highscore;
        ctx.fillStyle = 'black';
        ctx.fillText(highscore_text, w-ctx.measureText(highscore_text).width-5, 15);

        // Display the Controls in the bottom right.
        var controls_text = "Controls: " + "Movement = Arrow Keys";
        ctx.fillStyle = 'red';
        ctx.fillText(controls_text, w-ctx.measureText(controls_text).width-5, h-5);
        
        // Display the score in the bottom left.
        var score_text = "Score: " + score;
        ctx.fillStyle = 'blue';
        ctx.fillText(score_text, 5, h-5);
    }
    
    //Lets first create a generic function to paint cells
    function paint_cell(x, y, color)
    {
        var colors = ["red","yellow","orange","blue","green","purple","pink"];
        if (color=="rainbow") {
            color =  colors[Math.floor(Math.random()*colors.length)];
        }
        ctx.fillStyle = color;
        ctx.fillRect(x*cw, y*cw, cw, cw);
        ctx.strokeStyle = "white";
        ctx.strokeRect(x*cw, y*cw, cw, cw);
    }
    
    function check_collision(x, y, array)
    {
        //This function will check if the provided x/y coordinates exist
        //in an array of cells or not
        for(var i = 0; i < array.length; i++)
        {
            if(array[i].x == x && array[i].y == y)
             return true;
        }
        return false;
    }
    

    // KEY INPUT DETECTION
    //==================================================================================================================
    //Lets add the keyboard controls now
    $(document).keydown(function(e){
        var key = e.which;
        // We will add another clause to prevent reverse gear
        // Left Arrow
        if(key == "37" && d != "right") d = "left";
        // Up Arrow
        else if(key == "38" && d != "down") d = "up";
        // Right Arrow
        else if(key == "39" && d != "left") d = "right";
        // Down Arrow
        else if(key == "40" && d != "up") d = "down";
    })

    // This functions registers any Touch Inputs and changes the snakes direction accordingly.
     $(function() {
      $("#canvas").swipe( {
        swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
            if (direction == "up" || direction == "down" || direction == "left" || direction == "right") {
                if(direction == "left" && d != "right") d = "left";
                else if(direction == "up" && d != "down") d = "up";
                else if(direction == "right" && d != "left") d = "right";
                else if(direction == "down" && d != "up") d = "down";
            }
        },
        threshold:0,
        fingers:'all'
      });
    });

    // This bit controls the Default Browser Key Detection and disables the default usage (So arrow keys don't scroll the screen.)
    window.addEventListener("keydown", function(e) {
        // space and arrow keys
        if([32, 37, 38, 39, 40].indexOf(e.keyCode) > -1) {
            e.preventDefault();
        }
    }, false);
    //==================================================================================================================
    
    
    
    
})