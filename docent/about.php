<body>
<?php include "include/nav_docent.php"; ?>
					<div class="alert alert-info" role="alert">
						<div class="container">
							<div class="row">
								<div class="col-sm">
									<h3>Over LearnFlow RijnIjssel</h3>
									<p>
										LearnFlow RijnIjssel is een systeem dat studenten van de software development
										opleiding mbo aan rijn ijssel lerend te kwalificeren
									</p>
									<p>
										wat dit inhoud is dat de huidige examens vervangen zullen worden met trajecten van 10 weken
										waarin de voortgang en zaken ten ale tijden duidelijk zijn voor zowel docetn als student zelf
									</p>
									<h4>Adventure</h4>
									<p>
										uitleg over learnflow
									</p>
									<h4>Projecten</h4>
									<p>
										uitleg over learnflow
									</p>
									<h4>Reisondersteuning</h4>
									<p>
										uitleg over learnflow
									</p>
									<p>
										<i>
											<i class="fa-solid fa-quote-left"></i>
											Traditionele examens namen veel tijd en ruimte in
											<br/>
											&nbsp;&nbsp;&nbsp;&nbsp;We waren erg blij met de assistentie van LearnFlow!
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