<?php
                
                if(isset($_POST['Register'])) {
                        $Auth = new Authentication;
                        $Auth->Register($_POST['Username'],$_POST['Password'],$_POST['EMail']);
                }
?>

<?php if (isset($Auth->Error_Message)) { echo "<div class='Error'>".$Auth->Error_Message."</div>"; unset($Auth->Error_Message); } ?>

                    <b>Register</b>
                    <form action='?view=register' method='post'>
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
                                    E-Mail: 
                                </td>
                                <td>
                                    <input name='EMail' type='text'>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input name='Register' type='hidden' value='true'>
                                    <input type='submit' value='Register'>
                                </td>
                            </tr>
                        </table>
                    </form>