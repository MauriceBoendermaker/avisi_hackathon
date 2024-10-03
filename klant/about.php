<body>
<?php include "include/nav_klant.php"; ?>
					<div class="alert alert-info" role="alert">
						<div class="container">
							<div class="row">
								<div class="col-sm">
									<h3>Over Donkey Travel</h3>
									<p>
										Donkey Travel is 11 jaar geleden opgericht door eigenaar Loes de Korte.
										In die tijd heeft het bedrijf een goede reputatie opgebouwd op het gebied van milieuvriendelijke en CO2-neutrale vakanties.
										De hoofdactivitieit van Donkey Travel is het arrangeren en begeleiden van ezel-huifkar-tochten.
										Donkey Travel heeft inmiddels een twintigtal ezels met huifkarren rondrijden.
										Dat aantal breidt zich nog altijd uit.
									</p>
									<p>
										Na haar studie Culturele Antropologie en ontwikkelingssociologie heeft Loes de Korte een aantal jaren op het Ministerie van Sociale Zaken en Werkgelegenheid gewerkt.
										Al snel werd duidelijk dat ze daar niet op haar plaats was.
										Ze besloot haar twee passies, ezels en reizen, te combineren.
										Zeker door de toenemende vraag naar CO2-neutrale vakanties bleek de vraag naar dit soort reizen groot.
										Binnen een paar jaar reden er ezels met huifkarren van Donkey Travel door Europa en was het een winstgevende onderneming.
									</p>
									<h4>Adventure</h4>
									<p>
										Donkey Travel is een adventurebedrijf dat ezels met huifkarren verhuurt.
										De gasten boeken een Donkey adventure voor een ezel-huifkartocht langs de Donkey Travel locaties in Nederland, Belgie en Duitsland.
										Op de startlocatie krijgen de gasten een ezel met huifkar en een route.
										Ze trekken volgens de route een aantal dagen langs verschillende Donkey Travel locaties, waar ze overnachten en de ezels verzorgd worden.
										Op de laatste locatie van van hun route aangekomen, 'leveren' ze de ezel en de huifkar weer in.
									</p>
									<h4>Boekingen</h4>
									<p>
										De gasten boeken een all-in arrangement uit een aanbod van voorgeschreven routes.
										Ze geven aan waar ze willen starten, hoeveel dagen ze willen reizen en waar ze hun adventuretocht willen beëindigen.
										Donkey Travel reserveert vervolgens de startlocatie, de overnachtingsplaatsen en het eindpunt.
										De gasten krijgen een ezelvriendelijke route langs alle locaties.
									</p>
									<h4>Reisondersteuning</h4>
									<p>
										Een van de unique selling points van Donkey Travel is dat de gasten ook onderweg begeleiding krijgen.
										Bijvoorbeeld als gasten tijdens een dagetappe verdwalen, als er een technisch mankement aan de huifkar is of als de ezel geen stap meer wil zetten.
										Ook kan het gebeuren dat het bospad geblokkeerd is door een omgevallen boom.
										Donkey Travel staat haar gasten dan altijd telefonisch met raad en daad bij.
									</p>
									<p>
										<i>
											<i class="fa-solid fa-quote-left"></i>
											Toen er een boom over het bospad lag, weigerde onze ezel nog een stap te verzetten.
											<br/>
											&nbsp;&nbsp;&nbsp;&nbsp;We waren erg blij met de assistentie van Donkey Travel!
											<i class="fa-solid fa-quote-right"></i>
										</i>
									<h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Max en Ine uit Voorthuizen</h5>
									</p>
								</div>
								<div class="col-sm">
									<div class="text-center">
										<img src="../loes.jpg" class="rounded" alt="Eigenaresse Loes de Korte" width="348.75" height="391.5">
										<p>Eigenaresse Loes de Korte</p>
									</div>
									<div class='container1'>
										<div class='img background-img'></div>
										<div class='img foreground-img'></div>
										<input type="range" min="1" max="100" value="50" class="slider" name='slider' id="slider">
										<div class='slider-button'></div>
									</div>
								</div>
							</div>
						</div>

<?php include "include/footer.php" ?>
		</div>
	</div>
<script>
    $("#slider").on("input change", (e)=>{
        const sliderPos = e.target.value;
        // Update the width of the foreground image
        $('.foreground-img').css('width', `${sliderPos}%`)
        // Update the position of the slider button
        $('.slider-button').css('left', `calc(${sliderPos}% - 18px)`)
    });
</script>
</body>
</html>