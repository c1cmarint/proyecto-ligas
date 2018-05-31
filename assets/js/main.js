/* FUNCIÓN PARA HACER ANIMACIÓN AL IR A UN ID */
$(function(){
	$('a[href*=\\#]').click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var $target = $(this.hash);
			$target = $target.length && $target || $('[name=' + this.hash.slice(1) +']');
			if ($target.length) {
				var targetOffset = $target.offset().top;
				$('html,body').animate({scrollTop: targetOffset}, 1000);
			}
		}
	});
});

/* FUNCIÓN PARA ESCONDER Y MOSTRAR EL MENU */
$(function(){
	$('.menu-icon').click(function(){
		let header = $('header');
		let headerPosition = header.css('top');
		if(headerPosition == '0px') {
			header.css('top','-100%');
			$('#menu').css('display','block');
			$('#cross').css('display','none');
		} else {
			header.css('top','0');
			$('#menu').css('display','none');
			$('#cross').css('display','block');
		}
	});
});

/* FUNCCIÓN MOSTRAR JUGADORES PARTE ADMIN */

function desplegarJugadoresAdmin(id){
	var element = $('#'+id);
	var elementDisplay = element.css('display');
	
	if(elementDisplay == 'none'){
		element.show(1000);
	}else {
		element.hide(1000);
	}
}

/* FUNCIÓN PARA INSERTAR UN JUGADOR */

$('#enviarformulario').on('click', function() {
	var file = $('#file').prop('files')[0];
	var nombre = $('#nombre-form').val();
	var apellidos = $('#apellidos-form').val();
	var dni = $('#dni-form').val();
	var dorsal = $('#dorsal-form').val();
	var form = new FormData();
	
	if (nombre.match(/^\s*$/) || apellidos.match(/^\s*$/) || dni.match(/^\s*$/) || dorsal.match(/^\s*$/)) {
		$('#alert').empty();
		$('#alert').append('<div class="alert alert-danger">No puede haber ningún campo vacío.</div>');
	} else {
		if (!nombre.match(/^[a-zA-ZÀ-ÿ\u00f1\u00d1]*$/) || !apellidos.match(/^[a-zA-ZÀ-ÿ\u00f1\u00d1]*$/)) {
			$('#alert').empty();
			$('#alert').append('<div class="alert alert-danger">Los campos nombre y apellidos sólo pueden contener texto.</div>');
		} else {
			if (!dni.match(/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/)) {
				$('#alert').empty();
				$('#alert').append('<div class="alert alert-danger">El DNI introducido no es válido.</div>');
			} else {
				if (!dorsal.match(/^[1-9][0-9]?$|^100$/)) {
					$('#alert').empty();
					$('#alert').append('<div class="alert alert-danger">El dorsal debe estar entre el 1 y el 100.</div>');
				} else if($('#file').val().length == 0) {
					$('#alert').empty();
					$('#alert').append('<div class="alert alert-danger">No has seleccionado ninguna imágen.</div>');
				} else {
					form.append('file', file);
					form.append('nombre', nombre);
					form.append('apellidos', apellidos);
					form.append('dni',dni);
					form.append('dorsal', dorsal);
					form.append('id-equipo', $('#equipo-form').val());
					
					$.ajax({
						url: 'admin/insertarJugador.php',
						dataType: 'text',
						cache: false,
						contentType: false,
						processData: false,
						data: form,
						type: 'post',
						success: function(response) {
							console.log(response);
							if(response == true) {
								$('#alert').empty();
								$('#alert').append('<div class="alert alert-success">Te has inscrito correctamente.</div>');
								$('#file').val('');
								$('#nombre-form').val('');
								$('#apellidos-form').val('');
								$('#dni-form').val('');
								$('#dorsal-form').val('');
							} else if(response == false) {
								$('#alert').empty();
								$('#alert').append('<div class="alert alert-danger">El dorsal ya está cogido. Prueba con otro dorsal.</div>');
							} else if(response == 'existe dni') {
								$('#alert').empty();
								$('#alert').append('<div class="alert alert-danger">Ya estás inscrito en este equipo.</div>');
							} else {
								$('#alert').empty();
								$('#alert').append('<div class="alert alert-danger">Ha ocurrido un error.</div>');
							}
						}
					});
	
				}
			}

		}
	}

})

/* FUNCIÓN CARGAR LOS JUGADORES Y MOSTRAR LOS DESPLEGABLES */

function cargarJugadores(id_equipo,elemento,cantidad,equipo,tipo) {
	$.ajax({
		url: 'obtenerJugadores.php',
		type: 'post',
		data: {'id_equipo': id_equipo},
		success: function(response) { 
			var jugadores = $.parseJSON(response);
			
			elemento.empty();
			for (let i = 1; i <= cantidad; i++) {
				if(tipo == 'goles') {
					var result = '<p>Gol ' + i + '</p>';
					if(equipo == 'local') {
						result += '<select name="goleador-l-' + i + '" required>';
					} else {
						result += '<select name="goleador-v-' + i + '" required>';
					}
				} else if(tipo == 'amarillas') {
					var result = '<p>Amarilla ' + i + '</p>';
					if (equipo == 'local') {
						result += '<select name="amarillas-l-' + i + '" required>';
					} else {
						result += '<select name="amarillas-v-' + i + '" required>';
					}
				} else {
					var result = '<p>Roja ' + i + '</p>';
					if (equipo == 'local') {
						result += '<select name="rojas-l-' + i + '" required>';
					} else {
						result += '<select name="rojas-v-' + i + '" required>';
					}
				}
				
				for (let j = 0; j < Object.keys(jugadores).length; j++) {
					result += '<option value="' + jugadores[j].id + '">' + jugadores[j].dorsal + '. ' + jugadores[j].nombre + ' ' + jugadores[j].apellidos + '</option>';
				}
				result += '</select>';
				elemento.append(result);
			}

		}
	});
}

/* FUNCIÓN PARA CARGAR LOS JUGADORES CUANDO CAMBIA LA CANTIDAD DE GOLES */

function crearGoleadores(event,tipo,equipo) {
	var cantidad = event.target.value;
	
	if(tipo == 'goles') {
		if(equipo == 'local') {
			var elemento = $('#goles_l');
			var id_equipo = elemento.attr('value');
		} else if (equipo == 'visitante') {
			var elemento = $('#goles_v');
			var id_equipo = elemento.attr('value');
		}
	} else if (tipo == 'amarillas') {
		if (equipo == 'local') {
			var elemento = $('#amarillas_l');
			var id_equipo = elemento.attr('value');
		} else if (equipo == 'visitante') {
			var elemento = $('#amarillas_v');
			var id_equipo = elemento.attr('value');
		}
	} else {
		if (equipo == 'local') {
			var elemento = $('#rojas_l');
			var id_equipo = elemento.attr('value');
		} else if (equipo == 'visitante') {
			var elemento = $('#rojas_v');
			var id_equipo = elemento.attr('value');
		}
	}

	cargarJugadores(id_equipo,elemento,cantidad,equipo,tipo);
}