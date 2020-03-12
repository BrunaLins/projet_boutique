<?php
require_once('../inc/init.php');

//0 : Accessibilité 
if(!userAdmin()){
	header('location:' . PATH . 'index.php');
}

//traitements pour supprimer un membre : 
//....gestion_membres.php? action=supprimer &id=x

if(isset($_GET['action']) && $_GET['action'] == 'supprimer' ){
	// Cela signifie qu'une action de suppression est demandée
	if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){
		// cela signifie qu'il y a bien un id à supprimer dans l'URL... 
		$resultat = $pdo -> prepare("SELECT * FROM membre WHERE id_membre = :id");
		$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$resultat -> execute();
		$membre = $resultat -> fetch(); // pour récupérer le nom de la ou de(s) photo(s)
		
		if($resultat -> rowCount() > 0){
			// cela signifie que le membre existe bien
			$resultat = $pdo -> exec("DELETE FROM membre WHERE id_membre = $_GET[id]");
			if($resultat){
				// Signifie que tout est ok, la requete à bien fonctionné
				
				// supprimer la ou les photo(s) du membre
				$chemin_photo = __DIR__ . '/../img/' . $membre['photo'];
				
				if(file_exists($chemin_photo) && $membre['photo'] != 'default.jpg'){
					unlink($chemin_photo); // supprimer un fichier du server
				}
				// message de validation
				// redirection
				$_SESSION['validation'][] = 'Le membre <b>id' . $_GET['id'] . '</b> a bien été supprimé !'; 
				header('location:' . PATH . 'admin/gestion_membres.php');
				exit;
			}
		}
		else{
			$_SESSION['validation'][] = 'Le membre <b>id' . $_GET['id'] . '</b> n\'existe pas !'; 
			header('location:' . PATH . 'admin/gestion_membres.php');
			exit;
		}
	}
	else{
		header('location:' . PATH . 'admin/gestion_membres.php');
	}
}

//1 : Récupérer tous les membres 

$resultat = $pdo -> query("SELECT * FROM membre");
$membres = $resultat -> fetchAll();

//2 : Afficher les membres dans un debug 
//debug($membres);

//3 : Afficher les membres dans un tableau HTML (id/photo/reference/titre/categorie/prix/stock)




require_once('../inc/header.php');
// ../css/styles.css
?>

<h1>Gestion des membres</h1>

<a href="formulaire_membre.php" class="btn btn-primary m-2">AJOUTER UN membre</a>

<table class="table table-dark table-hover m-2">
	<thead>
		<th>id</th>
		<th>Prenom</th>
		<th>Nom</th>
		<th>Pseudo</th>
		<th>Email</th>
		<th colspan="3">Action</th>
	</thead>
	<tbody>

		<?php foreach($membres as $p) : extract($p)?>
		<tr title="<?= $description ?> - Public : <?= $public ?>">

			<td><?= $id_membre ?></td>
			<td><?= $prenom ?></td>
			<td><?= $nom ?></td>
			<td><?= $pseudo ?></td>
			<td><?= $email ?></td>
			<td><a href="" title="Voir le membre"> <i class="fas fa-eye text-primary"></i> </a></td>
			
			
			<td><a href="formulaire_membre.php?id=<?= $id_membre ?>" title="Modifier le membre"> <i class="fas fa-edit text-warning"></i> </a></td>
			
			
			<td><a href="?action=supprimer&id=<?= $id_membre ?>" title="Supprimer le membre" onclick="return confirm('Etes-vous certain de vouloir supprimer ce membre ?')"> <i class="fas fa-trash-alt text-danger"></i> </a></td>
			
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>






<?php
require_once('../inc/footer.php');
?>
