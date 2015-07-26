<script type='text/javascript'>

function SendSelectedNameToPHP()
{

var SearchList = document.getElementById("SearchList");
var SelectedUsername = SearchList.options[SearchList.selectedIndex].value;

$.ajax({
         url:  "https://dustinhendrickson.com/views/fightbot_change_selected_user.php", // php file where you want to send data
         type: "POST",
         data: {"SearchList" : SelectedUsername}, // this data will be sent
         success: function(data){
            //alert(SelectedUsername);
         },
         error: function(data){
            //alert("fail");
         }
});

}

</script>

<div class='ContentHeader'>FightBot - Please Read the <a target='_blank' href='https://github.com/DustinHendrickson/fight/blob/master/README.md'><u>README</u></a>.</div>
<i>Commands</i></br>
Create a new character: <b>/msg Fight|Bot @fight create</b></br>
See enemy stats: <b>/msg Fight|Bot @fight info playername</b></br>
Need help? <b>/msg Fight|Bot @fight help</b><br>
Want to try your luck at a quest without waiting? <b>/msg Fight|Bot @fight quest</b> this costs 15 exp.
<hr>
<div id='FightBotStats'><img src='../img/ajax-loader.gif'> <b>Loading Stats...</b></div>
<hr>
<form method='post' action='js/views/fightbot.php'>
View Another Players Info <i>(Select their name)</i><br>

<select id='SearchList' name='SearchList' style='width: 100%;' size='5' onchange='SendSelectedNameToPHP()'>
<?PHP
        $Connection = new Connection();
        $List_Array = array();
        $List_Results = $Connection->Custom_Query("SELECT * FROM users WHERE FightBot_Name IS NOT NULL", $List_Array, true);
        foreach ($List_Results as $Row) {
            if($UserToDisplay == $Row['FightBot_Name']) { $selected = "selected"; } else { $selected = ''; }
            echo "<option " . $selected . " value='" . $Row['FightBot_Name'] . "'>" . $Row['FightBot_Name']. "</option>";
        }
?>

</select>
</form>
<hr>
<iframe src="https://dustinhendrickson.com:7777" width="100%" height="50%"></iframe>
