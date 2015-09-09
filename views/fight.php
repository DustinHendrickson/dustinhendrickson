<script type="text/javascript">
    function ToggleTabs(SelectedTab) {
            var TabStyle = document.getElementById(SelectedTab).style.display;

            if (SelectedTab == "FightMainScreen") {
                document.getElementById("FightMainScreen").style.display="block";
                document.getElementById("FightShop").style.display="none";
                document.getElementById("FightQuest").style.display="none";
                document.getElementById("FightDuel").style.display="none";
                document.getElementById("FightInventory").style.display="none";
                document.getElementById("FightInspector").style.display="none";

                document.getElementById(SelectedTab.replace('Fight', '')).style.backgroundColor="green";
                document.getElementById("Shop").style.backgroundColor="";
                document.getElementById("Quest").style.backgroundColor="";
                document.getElementById("Duel").style.backgroundColor="";
                document.getElementById("Inventory").style.backgroundColor="";
                document.getElementById("Inspector").style.backgroundColor="";
            }

            if (SelectedTab == "FightShop") {
                document.getElementById("FightMainScreen").style.display="none";
                document.getElementById("FightShop").style.display="block";
                document.getElementById("FightQuest").style.display="none";
                document.getElementById("FightDuel").style.display="none";
                document.getElementById("FightInventory").style.display="none";
                document.getElementById("FightInspector").style.display="none";

                document.getElementById(SelectedTab.replace('Fight', '')).style.backgroundColor="green";
                document.getElementById("MainScreen").style.backgroundColor="";
                document.getElementById("Quest").style.backgroundColor="";
                document.getElementById("Duel").style.backgroundColor="";
                document.getElementById("Inventory").style.backgroundColor="";
                document.getElementById("Inspector").style.backgroundColor="";
            }

            if (SelectedTab == "FightQuest") {
                document.getElementById("FightMainScreen").style.display="none";
                document.getElementById("FightShop").style.display="none";
                document.getElementById("FightQuest").style.display="block";
                document.getElementById("FightDuel").style.display="none";
                document.getElementById("FightInventory").style.display="none";
                document.getElementById("FightInspector").style.display="none";

                document.getElementById(SelectedTab.replace('Fight', '')).style.backgroundColor="green";
                document.getElementById("MainScreen").style.backgroundColor="";
                document.getElementById("Shop").style.backgroundColor="";
                document.getElementById("Duel").style.backgroundColor="";
                document.getElementById("Inventory").style.backgroundColor="";
                document.getElementById("Inspector").style.backgroundColor="";
            }

            if (SelectedTab == "FightDuel") {
                document.getElementById("FightMainScreen").style.display="none";
                document.getElementById("FightShop").style.display="none";
                document.getElementById("FightQuest").style.display="none";
                document.getElementById("FightDuel").style.display="block";
                document.getElementById("FightInventory").style.display="none";
                document.getElementById("FightInspector").style.display="none";

                document.getElementById(SelectedTab.replace('Fight', '')).style.backgroundColor="green";
                document.getElementById("MainScreen").style.backgroundColor="";
                document.getElementById("Shop").style.backgroundColor="";
                document.getElementById("Quest").style.backgroundColor="";
                document.getElementById("Inventory").style.backgroundColor="";
                document.getElementById("Inspector").style.backgroundColor="";
            }

            if (SelectedTab == "FightInventory") {
                document.getElementById("FightMainScreen").style.display="none";
                document.getElementById("FightShop").style.display="none";
                document.getElementById("FightQuest").style.display="none";
                document.getElementById("FightDuel").style.display="none";
                document.getElementById("FightInventory").style.display="block";
                document.getElementById("FightInspector").style.display="none";

                document.getElementById(SelectedTab.replace('Fight', '')).style.backgroundColor="green";
                document.getElementById("MainScreen").style.backgroundColor="";
                document.getElementById("Shop").style.backgroundColor="";
                document.getElementById("Quest").style.backgroundColor="";
                document.getElementById("Duel").style.backgroundColor="";
                document.getElementById("Inspector").style.backgroundColor="";
            }

            if (SelectedTab == "FightInspector") {
                document.getElementById(SelectedTab.replace('Fight', '')).style.backgroundColor="green";
                document.getElementById("FightMainScreen").style.display="none";
                document.getElementById("FightShop").style.display="none";
                document.getElementById("FightQuest").style.display="none";
                document.getElementById("FightDuel").style.display="none";
                document.getElementById("FightInventory").style.display="none";
                document.getElementById("FightInspector").style.display="block";

                document.getElementById("MainScreen").style.backgroundColor="";
                document.getElementById("Shop").style.backgroundColor="";
                document.getElementById("Quest").style.backgroundColor="";
                document.getElementById("Duel").style.backgroundColor="";
                document.getElementById("Inventory").style.backgroundColor="";
            }

}
</script>

<?PHP
?>
<!--  MAIN PAGE FOR DISPLAYING CONTENT.-->
<div class='ContentHeader'> FIGHT! </div>

<div class='FightToolbar'>

    <div class="container" style="height: 25px">

            <center>
            <!--  DISPLAY SHOP BUTTON -->
            <button type="button" id="MainScreen" onclick="ToggleTabs('FightMainScreen')"> Main Screen </button>

            <!--  DISPLAY SHOP BUTTON -->
            <button type="button" id="Shop" onclick="ToggleTabs('FightShop')"> Shop </button>

            <!--  DISPLAY QUEST BUTTON -->
            <button type="button" id="Inventory" onclick="ToggleTabs('FightInventory')"> Inventory </button>

            <!--  DISPLAY QUEST BUTTON -->
            <button type="button" id="Quest" onclick="ToggleTabs('FightQuest')"> Quest </button>

            <!--  DISPLAY DUEL BUTTON -->
            <button type="button" id="Duel" onclick="ToggleTabs('FightDuel')"> Duel </button>

            <!--  DISPLAY DUEL BUTTON -->
            <button type="button" id="Inspector" onclick="ToggleTabs('FightInspector')"> Inspect </button>
            </center>
    </div>

</div>


<div id="FightMainScreen" name="FightMainScreen">
    <center> <img src="../img/VsMockupDraft.png"> </center>
    <!--  DISPLAY FIGHT_DISPLAY_BATTLES.PHP IN AN AUTO REFRESHING DIV.-->
    <div name='FightDisplay' id='FightDisplay' class='FightDisplay'> </div>
</div>

<div id="FightShop" name="FightShop" style="display: none;">
    Fight Shop!
    <div id="FightShopDisplay" name="FightShopDisplay" > </div>
</div>

<div id="FightQuest" name="FightQuest" style="display: none;">
    Quest Area
</div>

<div id="FightDuel" name="FightDuel" style="display: none;">
    Dueling Arena
</div>

<div id="FightInventory" name="FightInventory" style="display: none;">
    Your Inventory
    <div id="FightInventoryDisplay" name="FightInventoryDisplay" > </div>
</div>

<div id="FightInspector" name="FightInspector" style="display: none;">
    Inspect Other Players<br>
    <!--  SHOW PLAYER INSPECTOR WINDOW -->
    <div class='PlayerInspector'>
            View Another Players Info <i>(Select their name)</i><br>
            <select id='SearchList' name='SearchList' style='width: 100%;' size='5' onclick='this.form.submit()'>
            </select>
    </div>
    <div class="PlayerInformation"> </div>
</div>
