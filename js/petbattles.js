$(document).ready(function(){
    //Canvas stuff
    var canvas = $("#canvas")[0];
    var ctx = canvas.getContext("2d");
    var w = $("#canvas").width();
    var h = $("#canvas").height();

    
    //Lets save the cell width in a variable for easy control
    var cw = 32;
    var player_direction;
    var food;
    var grass = new Array();
    var restricted_block = new Array()
    var score;
    var moving = false;
    var keyisdown = {a:false, s:false, d:false, w:false};
    var currentmap = "";
    var isTransitioning = 'False';
    var gameSpeed = 120;
    var MAXX = (w / cw) - 1;
    var MINX = 0;
    var MAXY = (h / cw) -1;
    var MINY = 0;
    var WORLDMAXX = 15;
    var WORLDMAXY = 9;
    var WORLDMINX = 0;
    var WORLDMINY = 0;
    //GRID IS 25x15
    //X 0- 24
    //Y 0 - 14
    
    var player_position = {x:0, y:0};
    var world_position = {x:0, y:0};
    var map_mapping = [[]];

    setup_world_position();
    read_json(currentmap);
    init();


function read_json(MapName)
{
        var Path = "petbattles/maps/";
        var MapPath = Path.concat(MapName);
        $.getJSON(MapPath, function(OpenJson) {
            $.each(OpenJson["Grass"], function(Index,Value) {
                grass[Index] = {x: Value.GrassX,y: Value.GrassY};
                //alert("GrassX: " + grass[Index].x + " GrassY: " + grass[Index].y);
            });
            $.each(OpenJson["Restricted"], function(Index,Value) {
                restricted_block[Index] = {x: Value.BlockX,y: Value.BlockY};
                //alert("GrassX: " + grass[Index].x + " GrassY: " + grass[Index].y);
            });
        });
}

function saveGameState(nx, ny, map, wx, wy)
{
    localStorage["PlayerX"] = nx;
    localStorage["PlayerY"] = ny;
    localStorage["WorldX"] = wx;
    localStorage["WorldY"] = wy;
    localStorage["CurrentMap"] = map;
}

function resumeGame()
{
    player_position.x = parseInt(localStorage["PlayerX"]);
    player_position.y = parseInt(localStorage["PlayerY"]);
    world_position.x = parseInt(localStorage["WorldX"]);
    world_position.y = parseInt(localStorage["WorldY"]);
    currentmap = localStorage["CurrentMap"];
}

function init()
{

    resumeGame();

    if (typeof player_position.x == "undefined" || player_position.x == null || isNaN(player_position.x) ) { player_position.x = 0; }
    if (typeof player_position.y == "undefined" || player_position.y == null || isNaN(player_position.y) ) { player_position.y = 0; }
    if (typeof currentmap === "undefined" || currentmap == null) { currentmap = "map1.json"; world_position.x = 0; world_position.y = 0; }
    if (typeof world_position.x == "undefined" || world_position.x == null || isNaN(world_position.x) ) { world_position.x = 0; }
    if (typeof world_position.y == "undefined" || world_position.y == null || isNaN(world_position.y) ) { world_position.y = 0; }

    //Lets move the snake now using a timer which will trigger the paint function
    //every 60ms
    if(typeof game_loop != "undefined") clearInterval(game_loop);
    game_loop = setInterval(update, gameSpeed);

}


//This is the main loop where we draw and calculate position.
function update()
{
    if (isTransitioning == 'False') {
    moving =false;
    read_json(currentmap);
    //To avoid the snake trail we need to paint the BG on every frame
    //Lets paint the canvas now
    img = new Image();
    img.src = '../petbattles/images/GrassBackground';
    var ptrn = ctx.createPattern(img, 'repeat'); // Create a pattern with this image, and set it to "repeat".

    ctx.fillStyle = ptrn;
    ctx.fillRect(0, 0, w, h);
    ctx.strokeStyle = "black";
    ctx.strokeRect(0, 0, w, h);
    
    //The movement code for the snake to come here.
    //The logic is simple
    //Pop out the tail cell and place it infront of the head cell
    var nx = player_position.x;
    var ny = player_position.y;

    var world_direction = "";
    var path_is_blocked = false;
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

    // Look ahead 1 cell and set direction.
    if (moving == true) {
        if(player_direction == "right") nx++;
        else if(player_direction == "left") nx--;
        else if(player_direction == "up") ny--;
        else if(player_direction == "down") ny++;
    }


    // Here we check if we're transitioning from one map to another.
    if (nx < MINX || nx > MAXX || ny < MINY || ny > MAXY) {
        world_direction = "";
        if (nx < MINX && world_position.x - 1 >= WORLDMINX) { player_position.x = MAXX; world_direction = "Left"; }
        if (nx > MAXX && world_position.x + 1 <= WORLDMAXX) { player_position.x = MINX; world_direction = "Right"; }
        if (ny < MINY && world_position.y + 1 <= WORLDMAXY) { player_position.y = MAXY; world_direction = "Up"; }
        if (ny > MAXY && world_position.y - 1 >= WORLDMINY) { player_position.y = MINY; world_direction = "Down"; }
        if (world_direction == "Up" && world_position.y + 1 <= WORLDMAXY) { currentmap = map_mapping[world_position.x][world_position.y + 1]; world_position.y = world_position.y + 1;}
        if (world_direction == "Down" && world_position.y - 1 >= WORLDMINY) { currentmap = map_mapping[world_position.x][world_position.y - 1]; world_position.y = world_position.y - 1; }
        if (world_direction == "Left" && world_position.x - 1 >= WORLDMINX) { currentmap = map_mapping[world_position.x - 1][world_position.y]; world_position.x = world_position.x - 1; }
        if (world_direction == "Right" && world_position.x + 1 <= WORLDMAXX) { currentmap = map_mapping[world_position.x + 1][world_position.y]; world_position.x = world_position.x + 1; }
        saveGameState(player_position.x, player_position.y, currentmap, world_position.x, world_position.y);
    } else {
        //Check if the next cell is blocked.
        $.each(restricted_block, function(Index,Value) {
            if (nx == restricted_block[Index].x && ny == restricted_block[Index].y) {
                path_is_blocked = true;
            }
        }); 
        // It's not, let's move forward.
        if (path_is_blocked == false) {
            player_position.x = nx;
            player_position.y = ny;
            if(player_position.x != nx || player_position.y != ny) { saveGameState(nx, ny, currentmap, world_position.x, world_position.y); }
        }
       
    //Function to check if player is in the GRASS
    $.each(grass, function(Index,Value) {
        paint_tall_grass(grass[Index].x, grass[Index].y, "darkgreen");
            if(player_position.x == grass[Index].x && player_position.y == grass[Index].y)
            {
                var random = Math.floor((Math.random() * 100) + 1);
                if (random <= 20 && moving == true) {
                    isTransitioning = "True";
                    saveGameState(player_position.x, player_position.y, currentmap, world_position.x, world_position.y);
                    window.location.replace("https://dustinhendrickson.com/?view=petbattle_fight_wild&Story_Mode=True");
                }
            }
    });

    $.each(restricted_block, function(Index,Value) {
        paint_rock(restricted_block[Index].x, restricted_block[Index].y, "black");
    });

    }

    draw_player(player_position.x, player_position.y, player_direction);
    write_ui(nx,ny,world_position.x,world_position.y, world_direction);

    }
}

function write_ui(x,y,wx,wy,world_direction) 
{
    // Set UI Text Font settings.
    ctx.font = "15px Verdana";

    // Display the Controls in the bottom right.
    var controls_text = "Controls: " + "Movement = WASD Keys";
    ctx.fillStyle = 'black';
    ctx.fillText(controls_text, w-ctx.measureText(controls_text).width-5, h-5);
    
    // Display the score in the bottom left.
    var score_text = x + "," + y + " - " + currentmap;
    ctx.fillStyle = 'black';
    ctx.fillText(score_text, 5, h-5);

    // Display the score in the bottom left.
    var score_text = wx + "," + wy + " - " + world_direction;
    ctx.fillStyle = 'orange';
    ctx.fillText(score_text, 5, 15);
}

function draw_player(x,y,player_direction)
{
    var img = new Image();
    img.src = "../petbattles/images/trainer-down";
    if (player_direction == "right") { img.src = "../petbattles/images/trainer-right"; }
    if (player_direction == "left") { img.src = "../petbattles/images/trainer-left"; }
    if (player_direction == "down") { img.src = "../petbattles/images/trainer-down"; } 
    if (player_direction == "up") { img.src = "../petbattles/images/trainer-up"; } 
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

function paint_tall_grass(x, y)
{
    var img = new Image();
    img.src = "../petbattles/images/TallGrass";
    ctx.drawImage(img,x*cw,y*cw, cw, cw);
}

function paint_rock(x, y)
{
    var img = new Image();
    img.src = "../petbattles/images/Rock";
    ctx.drawImage(img,x*cw,y*cw, cw, cw);
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
$(document).keydown(function(e)
{
    var key = e.which;
    // We will add another clause to prevent reverse gear
    // Left Arrow
    if(key == "65") { 
        keyisdown.a = true;
    }

    if(key == "87") { 
        keyisdown.w = true;
    } 

    if(key == "68") { 
        keyisdown.d = true;
    } 

    if(key == "83") { 
        keyisdown.s = true;
    }

    })

// If the user stops key presses we stop movement.
$(document).keyup(function(e){
    var key = e.which;
    if(key == "65") {
        keyisdown.a = false;
    } 

     if(key == "87") {
        keyisdown.w = false;
    } 

     if(key == "68") {
        keyisdown.d = false;
    } 

     if(key == "83" ){
        keyisdown.s = false;
    }
    if(keyisdown.w == false && keyisdown.a == false && keyisdown.s == false && keyisdown.d == false)
    {
        moving = false;
    }
})

// This functions registers any Touch Inputs and changes the snakes direction accordingly.
 $(function() 
 {
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
window.addEventListener("keydown", function(e)
{
    // space and arrow keys
    if([32, 37, 38, 39, 40].indexOf(e.keyCode) > -1) {
        e.preventDefault();
    }
}, false);
//==================================================================================================================

 function setup_world_position()
{
    map_mapping[1] = [];
    map_mapping[2] = [];
    map_mapping[3] = [];
    map_mapping[4] = [];
    map_mapping[5] = [];
    map_mapping[6] = [];
    map_mapping[7] = [];
    map_mapping[8] = [];
    map_mapping[9] = [];
    map_mapping[10] = [];
    map_mapping[11] = [];
    map_mapping[12] = [];
    map_mapping[13] = [];
    map_mapping[14] = [];
    map_mapping[15] = [];

    map_mapping[0][0] = "map1.json";
    map_mapping[1][0] = "map2.json";
    map_mapping[2][0] = "map3.json";
    map_mapping[3][0] = "map4.json";
    map_mapping[4][0] = "map5.json";
    map_mapping[5][0] = "map6.json";
    map_mapping[6][0] = "map7.json";
    map_mapping[7][0] = "map8.json";
    map_mapping[8][0] = "map9.json";
    map_mapping[9][0] = "map10.json";
    map_mapping[10][0] = "map11.json";
    map_mapping[11][0] = "map12.json";
    map_mapping[12][0] = "map13.json";
    map_mapping[13][0] = "map14.json";
    map_mapping[14][0] = "map15.json";
    map_mapping[15][0] = "map16.json";

    map_mapping[0][1] = "map17.json";
    map_mapping[1][1] = "map18.json";
    map_mapping[2][1] = "map19.json";
    map_mapping[3][1] = "map20.json";
    map_mapping[4][1] = "map21.json";
    map_mapping[5][1] = "map22.json";
    map_mapping[6][1] = "map23.json";
    map_mapping[7][1] = "map24.json";
    map_mapping[8][1] = "map25.json";
    map_mapping[9][1] = "map26.json";
    map_mapping[10][1] = "map27.json";
    map_mapping[11][1] = "map28.json";
    map_mapping[12][1] = "map29.json";
    map_mapping[13][1] = "map30.json";
    map_mapping[14][1] = "map31.json";
    map_mapping[15][1] = "map32.json";

    map_mapping[0][2] = "map33.json";
    map_mapping[1][2] = "map34.json";
    map_mapping[2][2] = "map35.json";
    map_mapping[3][2] = "map36.json";
    map_mapping[4][2] = "map37.json";
    map_mapping[5][2] = "map38.json";
    map_mapping[6][2] = "map39.json";
    map_mapping[7][2] = "map40.json";
    map_mapping[8][2] = "map41.json";
    map_mapping[9][2] = "map42.json";
    map_mapping[10][2] = "map43.json";
    map_mapping[11][2] = "map44.json";
    map_mapping[12][2] = "map45.json";
    map_mapping[13][2] = "map46.json";
    map_mapping[14][2] = "map47.json";
    map_mapping[15][2] = "map48.json";

    map_mapping[0][3] = "map49.json";
    map_mapping[1][3] = "map50.json";
    map_mapping[2][3] = "map51.json";
    map_mapping[3][3] = "map52.json";
    map_mapping[4][3] = "map53.json";
    map_mapping[5][3] = "map54.json";
    map_mapping[6][3] = "map55.json";
    map_mapping[7][3] = "map56.json";
    map_mapping[8][3] = "map57.json";
    map_mapping[9][3] = "map58.json";
    map_mapping[10][3] = "map59.json";
    map_mapping[11][3] = "map60.json";
    map_mapping[12][3] = "map61.json";
    map_mapping[13][3] = "map62.json";
    map_mapping[14][3] = "map63.json";
    map_mapping[15][3] = "map64.json";

    map_mapping[0][4] = "map65.json";
    map_mapping[1][4] = "map66.json";
    map_mapping[2][4] = "map67.json";
    map_mapping[3][4] = "map68.json";
    map_mapping[4][4] = "map69.json";
    map_mapping[5][4] = "map70.json";
    map_mapping[6][4] = "map71.json";
    map_mapping[7][4] = "map72.json";
    map_mapping[8][4] = "map73.json";
    map_mapping[9][4] = "map74.json";
    map_mapping[10][4] = "map75.json";
    map_mapping[11][4] = "map76.json";
    map_mapping[12][4] = "map77.json";
    map_mapping[13][4] = "map78.json";
    map_mapping[14][4] = "map79.json";
    map_mapping[15][4] = "map80.json";


    map_mapping[0][5] = "map81.json";
    map_mapping[1][5] = "map82.json";
    map_mapping[2][5] = "map83.json";
    map_mapping[3][5] = "map84.json";
    map_mapping[4][5] = "map85.json";
    map_mapping[5][5] = "map86.json";
    map_mapping[6][5] = "map87.json";
    map_mapping[7][5] = "map88.json";
    map_mapping[8][5] = "map89.json";
    map_mapping[9][5] = "map90.json";
    map_mapping[10][5] = "map91.json";
    map_mapping[11][5] = "map92.json";
    map_mapping[12][5] = "map93.json";
    map_mapping[13][5] = "map94.json";
    map_mapping[14][5] = "map95.json";
    map_mapping[15][5] = "map96.json";

    map_mapping[0][6] = "map97.json";
    map_mapping[1][6] = "map98.json";
    map_mapping[2][6] = "map99.json";
    map_mapping[3][6] = "map100.json";
    map_mapping[4][6] = "map101.json";
    map_mapping[5][6] = "map102.json";
    map_mapping[6][6] = "map103.json";
    map_mapping[7][6] = "map104.json";
    map_mapping[8][6] = "map105.json";
    map_mapping[9][6] = "map106.json";
    map_mapping[10][6] = "map107.json";
    map_mapping[11][6] = "map108.json";
    map_mapping[12][6] = "map109.json";
    map_mapping[13][6] = "map110.json";
    map_mapping[14][6] = "map111.json";
    map_mapping[15][6] = "map112.json";

    map_mapping[0][7] = "map113.json";
    map_mapping[1][7] = "map114.json";
    map_mapping[2][7] = "map115.json";
    map_mapping[3][7] = "map116.json";
    map_mapping[4][7] = "map117.json";
    map_mapping[5][7] = "map118.json";
    map_mapping[6][7] = "map119.json";
    map_mapping[7][7] = "map120.json";
    map_mapping[8][7] = "map121.json";
    map_mapping[9][7] = "map122.json";
    map_mapping[10][7] = "map123.json";
    map_mapping[11][7] = "map124.json";
    map_mapping[12][7] = "map125.json";
    map_mapping[13][7] = "map126.json";
    map_mapping[14][7] = "map127.json";
    map_mapping[15][7] = "map128.json";

    map_mapping[0][8] = "map129.json";
    map_mapping[1][8] = "map130.json";
    map_mapping[2][8] = "map131.json";
    map_mapping[3][8] = "map132.json";
    map_mapping[4][8] = "map133.json";
    map_mapping[5][8] = "map134.json";
    map_mapping[6][8] = "map135.json";
    map_mapping[7][8] = "map136.json";
    map_mapping[8][8] = "map137.json";
    map_mapping[9][8] = "map138.json";
    map_mapping[10][8] = "map139.json";
    map_mapping[11][8] = "map140.json";
    map_mapping[12][8] = "map141.json";
    map_mapping[13][8] = "map142.json";
    map_mapping[14][8] = "map143.json";
    map_mapping[15][8] = "map144.json";

    map_mapping[0][9] = "map145.json";
    map_mapping[1][9] = "map146.json";
    map_mapping[2][9] = "map147.json";
    map_mapping[3][9] = "map148.json";
    map_mapping[4][9] = "map149.json";
    map_mapping[5][9] = "map150.json";
    map_mapping[6][9] = "map151.json";
    map_mapping[7][9] = "map152.json";
    map_mapping[8][9] = "map153.json";
    map_mapping[9][9] = "map154.json";
    map_mapping[10][9] = "map155.json";
    map_mapping[11][9] = "map156.json";
    map_mapping[12][9] = "map157.json";
    map_mapping[13][9] = "map158.json";
    map_mapping[14][9] = "map159.json";
    map_mapping[15][9] = "map160.json";

}


})