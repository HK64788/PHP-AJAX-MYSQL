

function ready() {

	let dropdownBtn = document.getElementById('hk-dropdown-btn')
	let dropdown = document.getElementById('hk-dropdown')

	dropdownBtn.addEventListener('click',function(){
		dropdown.classList.toggle('show')
	})



	let nav_btns = document.querySelectorAll('.users_nav_link');
	let nav_content = document.querySelectorAll('.nav-content');

	nav_btns.forEach(function(element){
		element.onclick = function(){
			for (let i = 0; i < nav_content.length;i++){
				nav_content[i].style.display = 'none'
			}

			for (let j = 0; j < nav_btns.length;j++){
				nav_btns[j].classList.remove('active')
			}

			nav_content[this.id].style.display = 'flex';
			nav_btns[this.id].classList.add('active')
		}
	});

	let modal = document.getElementById("hk-modal")
	function openModal(){
		modal.classList.toggle("d-block");
	}


	


}
document.addEventListener("DOMContentLoaded", ready);





