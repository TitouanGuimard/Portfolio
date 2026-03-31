                <div>
                    
                    <span class="reset">
                        <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">Tout Réinitialiser</a>
                    </span>
                    <form method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="filtreForm">
                        <input type="hidden" name="nt" id="nt-input" value="<?php echo $nt ?>">
                        <input type="hidden" name="recherche" id="recherche-input" value="<?php echo $recherche ?>">
                        <input type="hidden" name="rechercheNom" id="rechercheNom-input" value="<?php echo $nomRecherche ?>">
                        
                        <select name="tri" id="tris" class="tris">
                            <option value="" disabled <?= ($tri === null) ? 'selected' : '' ?>>Trier par :</option> 
                            <!--Selon la variable tri du post, cela selected le bon-->
                            <option value="pxCrois" <?= ($tri === 'pxCrois') ? 'selected' : '' ?>>Prix : ordre croissant</option> 
                            <option value="pxDecrois" <?= ($tri === 'pxDecrois') ? 'selected' : '' ?>>Prix : ordre décroissant</option>
                            <option value="ntCrois" <?= ($tri === 'ntCrois') ? 'selected' : '' ?>>Note : ordre croissant</option>
                            <option value="ntDecrois" <?= ($tri === 'ntDecrois') ? 'selected' : '' ?>>Note : ordre décroissant</option>
                            <!--<option value="dtCrois" ($tri === 'dtCrois') ? 'selected' : '' >Date publication: ordre croissant</option>
                            <option value="dtDecrois" ($tri === 'dtDecrois') ? 'selected' : '' >Date publication : ordre décroissant</option>-->
                        </select>
                        
                        
                        <div class="separateur"></div>
                        
                        <h1>Filtres</h1>

                        <h3>Vendeur </h3>
                        <hr/>
                        <?php 

                        $stmt = $bdd->prepare("SELECT DISTINCT codeCompte,pseudo FROM Vendeur");
                        $stmt->execute();
                        $pseudoVendeur = $stmt->fetchAll();
                        //print_r($pseudoVendeur);
                        
                        //print_r($libCats);
                        ?>
                        <select name="vendeur" id="vend" class="vend">
                                <option value="" disabled <?= ($vendeur === null) ? 'selected' : '' ?>>Choisir un vendeur :</option> 
                            <?php
                            foreach($pseudoVendeur as $pseudo){
                        
                                ?>
                                <option value="<?php echo $pseudo["codecompte"] ?>" <?= ($vendeur == $pseudo["codecompte"]) ? 'selected' : '' ?>><?php echo $pseudo["pseudo"] ?></option>
                            <?php }
                                ?>
                        </select>
                        <h3>Note</h3>
                        <hr/>
                        <div class="noter" id="stars" data-selected="<?php echo $nt ?>">
                            <span data-value="1">★</span>
                            <span data-value="2">★</span>
                            <span data-value="3">★</span>
                            <span data-value="4">★</span>
                            <span data-value="5">★</span>
                        </div>
                        <span id="note-value" style="display:none;">0</span>

                        <!-- Pour toute catégorie , pouvoir choisir laquelle on veut regarder.-->
                        <h3> Catégorie </h3>
                        <hr/>
                        <div style="display:flex;flex-direction:column;padding:10px;gap:5px;">
                        <?php 

                        $stmt = $bdd->prepare("SELECT DISTINCT libCat FROM SousCat ORDER BY libCat");
                        $stmt->execute();
                        $libCats = $stmt->fetchAll();
                        //print_r($libCats);
                        ?>
                            <select name="cat" id="cats" class="cats">
                                <option value="" disabled <?= ($cat === null) ? 'selected' : '' ?>>Choisir une catégorie :</option> 
                            <?php
                            foreach($libCats as $libCat){
                                ?>
                                <option value="<?php echo $libCat["libcat"] ?>" <?= ($cat === $libCat["libcat"]) ? 'selected' : '' ?>><?php echo $libCat["libcat"] ?></option>
                            <?php }
                                ?>
                            </select>
                        
                        </div> 
                        <h3>Prix</h3>
                        <hr/>
                        <!-- Slider -->
                         <div class="slider-container">
                            <div class="price-input-container">
                                <div class="price-input">
                                    <div class="price-field">
                                        
                                        <input type="number" class="min-input" value="<?php echo $pmin ? $pmin : 0 ?>" disabled>
                                    </div>
                                    <div class="price-field">
                                        <input type="number" class="max-input" value="<?php echo $pmax ? $pmax : $maxPrix ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="slider">
                                <div class="price-slider"></div>
                            </div>
                            <div class="range-input">
                                <input type="range" class="min-range" min="0" max="<?php echo $maxPrix?>" value="<?php echo $pmin ? $pmin : 0?>" step="1" name="pmin">
                                <input type="range" class="max-range" min="0" max="<?php echo $maxPrix?>" value="<?php echo $pmax ? $pmax : $maxPrix?>" step="1" name="pmax">
                            </div>
                        </div>
                    </form>
                </div>
            