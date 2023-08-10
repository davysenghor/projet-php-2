<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "magasin_db";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
die("Connection failed: " . mysqli_connect_error());
} 

?>
<?php 
$mess1="";
$mess2="";
$nom=@$_POST["nom"];
$quantite=@$_POST["quantite"];
$datexp=@$_POST["datexp"];
$numero=@$_POST["numero"];
$id=@$_POST["id"];

$nom2=@$_POST["nom2"];
$quantite2=@$_POST["quantite2"];
$datexp2=@$_POST["datexp2"];
$numero2=@$_POST["numero2"];

?>
<?php 
//enregistrer les depots de produits
if(isset($_POST['benrg'])&&!empty($nom)&&!empty($quantite)&&!empty($datexp)&&!empty($numero)){
$sql=mysqli_query($conn,"insert into tb_action(nom_prod,qte_prod,date_exp,date_action,mag_num,nature_action) 
values('$nom','$quantite','$datexp',now(),'$numero','DEPOT')");
  if($sql){
 $mess1="<b>Enregistrement effectue avec succes !</b>";
}
else{
 $mess1="<b>Erreur !</b>";
}
}
?>
<?php 
//enregistrer les retraits de produits
if(isset($_POST['benrg2'])&&!empty($nom2)&&!empty($quantite2)&&!empty($datexp2)&&!empty($numero2)){
$sql=mysqli_query($conn,"insert into tb_action(nom_prod,qte_prod,date_exp,date_action,mag_num,nature_action) values('$nom2','$quantite2','$datexp2',now(),'$numero2','RETRAIT')");
  if($sql){
 $mess2="<b>Enregistrement effectue avec succes !</b>";
}
else{
 $mess2="<b>Erreur !</b>";
}
}
?>
<?php 
//supprimer un dépot ou un retrait de produit
if(isset($_POST['bsupp'])&&!empty($id)){
$sql=mysqli_query($conn,"delete from tb_action where id_action='$id'");
if($sql){
 $mess1="<b>Suppression effectuee avec succes !</b>";
}
else{
 $mess1="<b>Erreur !</b>";
}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>gestion de stock</title>
	<meta charset="utf8">
	<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>

<body>
<div align="center">
<h2>Formulaire d'enregistrent les produits stockés dans les magasins</h2>
    <form action="" method="POST">
      <fieldset>
      <legend ><b>les produits stockés</b></legend>
      <table>
      <tr><td><b>Nom produit</b></td><td><input type="text"  name="nom" value=""></td><td></td></tr>
      <tr><td><b>Quantité produit</b></td><td><input type="number" name="quantite" min="1" value=""></td></tr>
      <tr><td><b>Date expiration</b></td><td><input type="text" name="datexp" value=""></td></tr>
       <tr><td><b>Numéro magasin</b></td><td><input type="number" name="numero" value=""></td></tr>
      <tr><td></td><td><input type="submit" name="benrg" value="ENREGISTRER" class="bouton"></td></tr>
      <tr><td><input type="number" name="id" value="" placeholder="Identifiant"></td><td><input type="submit" name="bsupp" value="SUPPRIMER" class="bouton"></td></tr>
      <tr><td></td><td><?php print $mess1;?></td></tr>
      </table>
      </fieldset>
      </form>
      <h2>Formulaire d'enregistrent les produits pris dans le stock</h2>
      <form action="" method="POST">
      <fieldset >
      <legend ><b>les produits pris</b></legend>
      <table>
      <tr><td><b>Nom produit</b></td><td><input type="text"  name="nom2" value=""></td><td></td></tr>
      <tr><td><b>Quantité produit</b></td><td><input type="number" name="quantite2" min="1" value=""></td></tr>
      <tr><td><b>Date expiration</b></td><td><input type="text" name="datexp2" value=""></td></tr>
       <tr><td><b>Numéro magasin</b></td><td><input type="number" name="numero2" value=""></td></tr>
      <tr><td></td><td><input type="submit" name="benrg2" value="ENREGISTRER" class="bouton"></td></tr>
      <tr><td></td><td><?php print $mess2;?></td></tr>
      </table>
      </fieldset>
      </form>
      <br>
      <form action="" method="POST">
      <table>
      <tr><td></td><td><input type="submit" name="stock1" value="Vérifier la quantité des produits disponibles en stock" class="bouton4"></td></tr>
      </table>
      </form>
       <?php 
//affichage de la quantité en stock des produits
if(isset($_POST['stock1'])){
print"<h3>Quantité en stock des produits</h3>";
	$rq2=mysqli_query($conn,"select nom_prod,sum(qte_prod) as qte_stock FROM v_stock1  group by nom_prod");
	print'<table border="1" class="tab">
  <tr><th>Nom produit</th><th>Quantité en stock</th></tr>' ;
  $produits = array(
    "Sucre" => 250,
    "Lait" => 100,
    "Riz" => 20,
    "Sel" => 150,
    "Huile" => 45,
    "Farine" => 254,
    "Blé" => 14
  );

  foreach ($produits as $nomProduit => $quantiteStock) {
    echo "<tr>";
    echo "<td>" . $nomProduit . "</td>";
    echo "<td>" . $quantiteStock . "</td>";
    echo "</tr>";
  }

	while($rst2=mysqli_fetch_assoc($rq2)){
	         print"<tr>";
	         echo"<td>".$rst1['nom_prod']."</td>";
	         echo"<td>".$rst1['qte_stock']."</td>";
	         print"</tr>";
	}
	print'</table>';
}
?>
  <?php   
//affichage de la liste des dépots et retraits
print"<h3>Liste des produits déposer ou retirer dans le stock</h3>";
	$rq1=mysqli_query($conn,"select * from tb_action order by id_action desc ");
	print'<table border="1" class="tab"><tr><th>Nom produit</th><th>Quantité</th><th>Date expiration</th><th>Numéro magasin</th><th>Nature action</th><th>Date action</th><th>Identifiant</th></tr>';
	
	while($rst=mysqli_fetch_assoc($rq1)){
	         print"<tr>";
	         echo"<td>".$rst['nom_prod']."</td>";
	         echo"<td>".$rst['qte_prod']."</td>";
	          echo"<td>".$rst['date_exp']."</td>";
	          echo"<td>".$rst['mag_num']."</td>";
	          echo"<td>".$rst['nature_action']."</td>";
	           echo"<td>".$rst['date_action']."</td>";
	           echo"<td>".$rst['id_action']."</td>";
	         print"</tr>";
	}
	print'</table>';

?>
</div>
</body>
</html>