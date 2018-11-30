<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                                <label>kies categorie en druk op sorteer.</label>
                                <select name="catselect[]">
                                    <?php 
                                        $cat_sql = "SELECT * FROM categorieen";
                                        if ($cresult = $connection->query($cat_sql)) {
                                            while($row = $cresult->fetch_array(MYSQLI_ASSOC)) {
                                    ?>
                                               <option value="<?php echo $row['naam']; ?>">
                                                <?php
                                            }
                                        }
                                                ?>
                                </select>
                                <input type="submit" value="Sorteer" class="btn btn-outline-success"/>  
                            </form> 
