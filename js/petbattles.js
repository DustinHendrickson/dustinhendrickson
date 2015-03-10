$(document).ready(function(){
    //Canvas stuff
    var canvas = $("#canvas")[0];
    var ctx = canvas.getContext("2d");
    var w = $("#canvas").width();
    var h = $("#canvas").height();
    
    //Lets save the cell width in a variable for easy control
    var cw = 50;
    var player_direction;
    var food;
    var score;
    var moving = false;
    var keyisdown = {a:false, s:false, d:false, w:false};
    
    var player_position = {x:0, y:0};
    
    function init()
    {
        create_player();
        create_food(); //Now we can see the food particle
        //finally lets display the score
        score = 0;

        //Lets move the snake now using a timer which will trigger the paint function
        //every 60ms
        if(typeof game_loop != "undefined") clearInterval(game_loop);
        game_loop = setInterval(update, 120);
    }
    init();
    
    function create_player()
    {
        player_position.x = 2;
        player_position.y = 2;
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

    //This is the main loop where we draw and calculate position.
    function update()
    {
        //To avoid the snake trail we need to paint the BG on every frame
        //Lets paint the canvas now
        ctx.fillStyle = "white";
        ctx.fillRect(0, 0, w, h);
        ctx.strokeStyle = "black";
        ctx.strokeRect(0, 0, w, h);
        
        //The movement code for the snake to come here.
        //The logic is simple
        //Pop out the tail cell and place it infront of the head cell
        var nx = player_position.x;
        var ny = player_position.y;
        //These were the position of the head cell.
        //We will increment it to get the new head position
        //Lets add proper direction based movement now
        if (keyisdown.a == true) {
            player_direction="left";
            moving=true;
        } else {
            if(keyisdown.s == true) {
                player_direction="down";
                moving=true;
            } else {
                if(keyisdown.d == true) {
                    player_direction="right";
                    moving=true;
                } else {
                    if(keyisdown.w == true) {
                        player_direction="up";
                        moving=true;
                    }
                }
            }
        }

        if (moving == true) {
            if(player_direction == "right") nx++;
            else if(player_direction == "left") nx--;
            else if(player_direction == "up") ny--;
            else if(player_direction == "down") ny++;
        }
                
        if(nx == food.x && ny == food.y)
        {
            score++;
            //Create new food
            create_food();
        }

        player_position.x = nx;
        player_position.y = ny;
        // paint the main char
        draw_player(nx, ny);
        
        //Lets paint the food
        paint_cell(food.x, food.y, "green");

        //Lets paint the score
        write_ui(score);
    }

    function write_ui(score) 
    {
        // Set UI Text Font settings.
        ctx.font = "15px Verdana";

        // Display the Controls in the bottom right.
        var controls_text = "Controls: " + "Movement = Arrow Keys";
        ctx.fillStyle = 'black';
        ctx.fillText(controls_text, w-ctx.measureText(controls_text).width-5, h-5);
        
        // Display the score in the bottom left.
        var score_text = "Score: " + score;
        ctx.fillStyle = 'green';
        ctx.fillText(score_text, 5, h-5);
    }
    
    function draw_player(x,y)
    {
        var img = new Image();
        img.src = "../petbattles/images/trainer";
        ctx.drawImage(img,x*cw,y*cw, cw, cw);
    }
    //Lets first create a generic function to paint cells
    function paint_cell(x, y, color)
    {
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
        if(key == "37") { 
            keyisdown.a = true;
        }

        if(key == "38") { 
            keyisdown.w = true;
        } 

        if(key == "39") { 
            keyisdown.d = true;
        } 

        if(key == "40") { 
            keyisdown.s = true;
        }
         
        })

    // If the user stops key presses we stop movement.
    $(document).keyup(function(e){
        var key = e.which;
        if(key == "37") {
            keyisdown.a = false;
        } 

         if(key == "38") {
            keyisdown.w = false;
        } 

         if(key == "39") {
            keyisdown.d = false;
        } 

         if(key == "40" ){
            keyisdown.s = false;
        }
        if(keyisdown.w == false && keyisdown.a == false && keyisdown.s == false && keyisdown.d == false)
        {
            moving = false;
        }
    })

    // This functions registers any Touch Inputs and changes the snakes direction accordingly.
     $(function() {
      $("#canvas").swipe( {
        swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
            if (direction == "up" || direction == "down" || direction == "left" || direction == "right") {
                if(direction == "left" && player_direction != "right") player_direction = "left";
                else if(direction == "up" && player_direction != "down") player_direction = "up";
                else if(direction == "right" && player_direction != "left") player_direction = "right";
                else if(direction == "down" && player_direction != "up") player_direction = "down";
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