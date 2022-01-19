<header>
<!-- <?php if ($user) : ?>
    <a class="popUp" href="/logout.php/"><img src="img/outline_logout_white_24dp.png" alt="Se déconnecter"><span>Se déconnecter</span></a>
    <a class="popUp" href="/profile.php/"><img src="img/outline_account_box_white_24dp.png" alt="Profil"><span>Profil</span></a>
<?php else : ?>
     <a class="popUp" href="/register.php/"><img src="img/outline_assignment_white_24dp.png" alt="S'inscrire"><span>S'inscrire</span></a>
     <a class="popUp" href="/login.php"><img src="img/outline_login_white_24dp.png" alt="Se connecter"><span>Se connecter</span></a>
<?php endif; ?> -->



<div class="menu">

<a href="/"><img src="/img/logo3.png" alt=""></a>
<a href=""><span class="un">About</span></a>

<?php if ($user) : ?>      
    <a href="/profile.php/"><span class="un">Profil</span></a>
    <a href="/logout.php/"><span class="un">Déconnexion</span></a>

<?php else : ?>
     
     <a href="/login.php"><span class="un">Connexion</span></a>
     <a href="/register.php/"><span class="un">Inscription</span></a>

<?php endif; ?>

</div>
</header>