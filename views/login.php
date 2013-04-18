<?php
                
                if(isset($_POST['Login'])) {
                        $Auth = new Authentication;
                        $Auth->Login($_POST['Username'],$_POST['Password']);
                        if (isset($Auth->Error_Message)) { echo "<div class='Error'>".$Auth->Error_Message."</div>"; unset($Auth->Error_Message); }
                }
?>                
                    <b>Login</b>
                    <form action='?view=login' method='post'>
                        <table>
                            <tr>
                                <td>
                                    Username: 
                                </td>
                                <td>
                                    <input name='Username' type='text'>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Password: 
                                </td>
                                <td>
                                    <input name='Password' type='password'>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input name='Login' type='hidden' value='true'>
                                    <input type='submit' value='Login'>
                                </td>
                            </tr>
                        </table>
                    </form>