

	<script>  
		var dropdown = document.getElementsByClassName("dropdown");
		
		for (var i = 0; i < dropdown.length; i++) {
			dropdown[i].addEventListener("click", function() {
				var list_items = document.getElementsByClassName("list_items");
				// console.log(list_items)
				for(var j=0; j < list_items.length; j++) {
					if(list_items[j].classList.contains('active')) {
						list_items[j].classList.remove('active')
					} 
				}				
				var listItem = this.children;
				listItem[0].classList.toggle("active") 
			})
		}

		function myFunction() {
			var x = document.getElementById("myNavbar");
			console.log(x.className)
			if (x.className === "top-menu-content navbar") {
				x.className += " responsive";
			} else {
				x.className = "top-menu-content navbar";
			}
  		
		}
   </script> 
   </body>
</html>
<?php
	wp_footer();
?>