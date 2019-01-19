jQuery(function($){
	$(document).ready(function() {
		createDataTable();
		/*
		$.ajax({
	    	method: "GET",
	    	url: '/am-admin/authors/all',
	    	data: {
	    		"limit": 800,
				"skip": 0,
				"inventory": 1,
				"_token": $('#_token').val()
	    	}
	    }).done((resp) => {
	    	console.log(resp)
	    }).catch((err) => {
	    	console.log(err)
	    })*/
	});
});

const language = {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Autores por pagina _MENU_",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando autores del _START_ al _END_ de un total de _TOTAL_ autores",
    "sInfoEmpty":      "Mostrando autores del 0 al 0 de un total de 0 autores",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ autores)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar autores:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando autores...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
};

const createDataTable = function() {
	var table = $('table.authors').DataTable( {
		language: language,
		lengthMenu: [[50, 100, 300, -1], [50, 100, 300, "Todas"]],
	    ajax: {
	    	method: "GET",
	    	url: '/am-admin/authors/all',
	    	data: {
	    		"limit": 800,
				"skip": 0,
				"inventory": 1,
				"_token": $('#_token').val()
	    	}
	    },
	    columns: [
	    	{ 
	    		data: "thumbnail",
	    		className: "image",
	    		"render": function (data, type, JsonResultRow, meta) {
	    			if(JsonResultRow.thumbnail !== null) {
                    	return '<img src="'+ JsonResultRow.thumbnail + '">';
                    } else {
                    	return '<img src="https://amolca.webussines.com/uploads/images/no-image.jpg">';
                    }
                } 
            },
	    	{ 	
	    		data: "title",
	    		className: "title"
	    	},
	    	{ 	
	    		data: "taxonomies",
	    		className: "specialty",
	    		render: function(data, type, JsonResultRow, meta) {
	    			if(JsonResultRow.taxonomies !== null && JsonResultRow.taxonomies !== undefined && JsonResultRow.taxonomies.length > 1) {
	    				return JsonResultRow.taxonomies[1].title;
	    			} else {
	    				return 'Sin especialidad';
	    			}
	    		}
	    	},
	    	{ 	
	    		data: "_id",
	    		className: "actions",
	    		"render":  function (data, type, JsonResultRow, meta) {
	    			let str = `<a class="edit" href="/am-admin/autores/${JsonResultRow.id}">
				                    <span class="icon-mode_edit"></span>
				                </a>

				                <a class="delete">
				                    <span class="icon-trash"></span>
				                </a>
		                  	`;
                  	return str;
                }
	    	},
	    ]
	});

	DeleteAuthor('.data-table tbody', table);

	$('#DataTables_Table_0_length select').formSelect();
	$('.dataTables_filter input[type="search"]').attr('placeholder', 'Escribe una palabra clave para encontrar una autor');
}

const DeleteAuthor = function(tbody, table) {

	$(tbody).on('click', '.delete', function() {

		let data = table.row($(this).parents("tr")).data();

		let alerta = confirm('Seguro que deseas eliminar permanentemente el libro: ' + data.title);

		if(alerta) {
			if($('.loader').hasClass('hidde'))
				$('.loader').removeClass('hidde')

			$.ajax({
				type: "DELETE",
				url: "/am-admin/autores/" + data.id,
				data: { "_token": $('#_token').val() }
			}).done(function(resp) {
				console.log(resp)

				$('.data-table').DataTable().ajax.reload();

				$('table.authors').DataTable().on('draw', function() {
					if(!$('.loader').hasClass('hidde'))
						$('.loader').addClass('hidde')

					let toastMsg = 'Se elminó exitosamente el autor: ' + data.name + '.';
					M.toast({html: toastMsg, classes: 'green accent-4 bottom'});
				})

			}).catch(function(err) {
				console.log(err)
			})
		}

	});

}