<?php
require_once('../inc/init.php');


//1 : Récupérer tous les produits 

$resultat = $pdo -> query("SELECT * FROM produit");
$produits = $resultat -> fetchAll();

//2 : Afficher les produits dans un debug 
debug($produits);

//3 : Afficher les produits dans un tableau HTML (id/photo/reference/titre/categorie/prix/stock)

require_once('../inc/header.php');
// ../css/styles.css
?>
<h1>Gestion des produits</h1>
<table class="table table-dark table-hover m-2">
	<thead>
		<?php 
			for($i=0; $i < $resultat -> ColumnCount(); $i++){ 
			$meta = $resultat -> getColumnMeta($i);
				echo '<th>' . ucfirst(str_replace('_', ' ', $meta['name'])) . '</th>'; 
			}
		?>
	</thead>
	<tbody>
		<?php foreach($produits as $p) :?>
		<tr>
			<?php foreach($p as $indice => $info) :?>
				<?php if($indice != 'photo'): ?>
				<td><?= $info ?></td>
				<?php else :  ?>
				<td><img src="<?= PATH ?>img/<?= $info ?>" height="60px" /></td>
				<?php endif; ?>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php
require_once('../inc/footer.php');
?>
