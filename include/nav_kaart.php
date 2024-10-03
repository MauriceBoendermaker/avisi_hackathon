<?php include "include/head.php"; ?>
<div class="container">
	<div class="crud-container">
		<h2>Donkey Travel Administrative Tools</h2>
		<ul class="nav nav-tabs">
			<li class="nav-item"><a class="nav-link" aria-current="page" href="index">Welcome</a></li>
			<li class="nav-item"><a class="nav-link" href="boekingen">Boekingen</a></li>
			<li class="nav-item"><a class="nav-link active" href="gasten">Beheer</a></li>
			<li class="nav-item"><a class="nav-link active" href="kaart">Kaart</a></li>
			<li class="nav-item ms-auto"><a class="nav-link text-danger" href="logout">Logout</a></li>
		</ul>
		<div class="crud-form row mx-0 ps-0">
			<div class="col-md-auto">
				<ul class="nav nav-pills flex-column nav-fill">
					<li class="nav-item">
						<a id="nav-gasten" class="nav-link" aria-current="page" href="gasten">Gasten</a>
					</li>
					<li class="nav-item">
						<a id="nav-herbergen" class="nav-link" href="herbergen">Herbergen</a>
					</li>
					<li class="nav-item">
						<a id="nav-restaurants" class="nav-link" href="restaurants">Restaurants</a>
					</li>
					<li class="nav-item">
						<a id="nav-tochten" class="nav-link" href="tochten">Tochten</a>
					</li>
					<li class="nav-item">
						<a id="nav-statussen" class="nav-link" href="status">Statussen</a>
					</li>
				</ul>
			</div>
			<div class="col-md-10">