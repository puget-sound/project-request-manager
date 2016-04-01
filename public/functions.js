
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

$( document ).ready(function() {
  $('#editarea').wysihtml5({
    toolbar: {
      "html": true,
      "link": true,
      "image": false,
      "blockquote": false
    }
  	});

  	$.ajaxSetup({
   		headers: {
    		'X-CSRF-Token' : $('meta[name=_token]').attr('content')
   		}
  	});

  	checkNotifications();


  	$('a.flag').click(function(e) {
	   e.preventDefault();
	   $.ajax({
	    context: $(this),
	    url: $(this).attr('href'),
	    type: 'POST',
	    success: function(data) {
			$(this).css("color", data.color);
			checkNotifications();
	    },
	    error: function(data) {
	    	alert('error');
	    }
	   });
	});

	$('.manageCheck').change(function(e) {
		console.log($(this).attr('href'));
		e.preventDefault();
		$.ajax({
			context: $(this),
			url: $(this).attr('href'),
			type: 'POST',
			success: function(data) {
				//$(this).prop('checked', data.inputSetting);
			},
			error: function(data) {
				alert('error');
			}
		});
	});

	$('#deleteModal').on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget) // Button that triggered the modal
	  var type = button.data('prmtype') // Extract info from data-* attributes
	  var prm_value = button.data('prmval') // Extract info from data-* attributes
	  var prm_id = button.data('prmid')
	  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
	  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
	  var modal = $(this)
	  modal.find('#user-delete').attr("href", "users/" + prm_id + "/delete")
	  modal.find('.modal-title').text('Delete ' + type + '?')
	  modal.find('.modal-body').html("Are you sure you want to remove <strong>" + prm_value + "</strong> from the Project Request Manager? <br><br> <small class='text-muted'>Removing this user will also remove any association to Project Owners they may have had.</small>");
	})

	$('#deleteProjectModal').on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget) // Button that triggered the modal
	  var type = button.data('prmtype') // Extract info from data-* attributes
	  var prm_value = button.data('prmval') // Extract info from data-* attributes
	  var prm_id = button.data('prmid')
	  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
	  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
	  var modal = $(this)
	  modal.find('#user-delete').attr("href", "users/" + prm_id + "/delete")
	  modal.find('.modal-title').text('Delete ' + type + '?')
	  modal.find('.modal-body').html("Are you sure you want to remove <strong>" + prm_value + "</strong> from the Project Request Manager? <br><br> <small class='text-muted'>THIS ACTION IS PERMANENT. DATA WILL NOT BE RECOVERED. (okay, that's a lie, it's still in the DB somewhere...)</small>");
	})

	$('#updateStatus').on('show.bs.modal', function (event) {
		//Get all the data
		var button = $(event.relatedTarget) // Button that triggered the modal
		var type = button.data('prmtype') // Extract info from data-* attributes
		var prm_value = button.data('prmval') // Extract info from data-* attributes
		var prm_id = button.data('prmid')
		var status_code = "";
		var statusXlat = "";
		//Grab the AJAX Data we need via AjaxController
		$.ajax({
			context: $(this),
			url: '//tsprojects.pugetsound.edu/request/' + prm_id + '/ajax/status',
			type: 'POST',
			success: function(data) {
				status_code = data.statusCode;
				statusXlat = data.statusXlat;
				var modal = $(this);
				$('#project_id_hidden').val(prm_id);
				$('#updateStatusSelect').val(status_code).change();
				//modal.find('#user-update').attr("href", "request/" + prm_id + "/update/status/");
				modal.find('.modal-title').text('Update Status: ' + prm_value);
				modal.find('.modal-body-head').html("<p>Current Status: <strong>" + statusXlat + "</strong></p>");
			},
			error: function(data) {
				alert('error');
			}
		});
		// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		//Set up the modal
		
		// modal.find('.modal-body').html("Are you sure you want to remove <strong>" + prm_value + "</strong> from the Project Request Manager? <br><br> <small class='text-muted'>THIS ACTION IS PERMANENT. DATA WILL NOT BE RECOVERED. (okay, that's a lie, it's still in the DB somewhere...)</small>");
	})

	$('#sprintAssign').on('show.bs.modal', function (event) {
			//Get all the data
			var button = $(event.relatedTarget) // Button that triggered the modal
			var type = button.data('prmtype') // Extract info from data-* attributes
			var prm_value = button.data('prmval') // Extract info from data-* attributes
			var prm_id = button.data('prmid')
			//Grab the AJAX Data we need via AjaxController
			$.ajax({
				context: $(this),
				url: '//tsprojects.pugetsound.edu/request/' + prm_id + '/ajax/status',
				type: 'POST',
				success: function(data) {
					var modal = $(this);
					$('#project_id_assign').val(prm_id);
					modal.find('.modal-title').text("Assign " + prm_value + " to a Sprint");
				},
				error: function(data) {
					alert('error');
				}
			});
			// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
			// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			//Set up the modal
			
			// modal.find('.modal-body').html("Are you sure you want to remove <strong>" + prm_value + "</strong> from the Project Request Manager? <br><br> <small class='text-muted'>THIS ACTION IS PERMANENT. DATA WILL NOT BE RECOVERED. (okay, that's a lie, it's still in the DB somewhere...)</small>");
		})

	$('#sprintDeassign').on('show.bs.modal', function (event) {
			//Get all the data
			var button = $(event.relatedTarget) // Button that triggered the modal
			var type = button.data('prmtype') // Extract info from data-* attributes
			var prm_value = button.data('prmval') // Extract info from data-* attributes
			var prm_id = button.data('prmid')
			//Grab the AJAX Data we need via AjaxController
			$.ajax({
				context: $(this),
				url: '//tsprojects.pugetsound.edu/request/' + prm_id + '/ajax/status',
				type: 'POST',
				success: function(data) {
					var modal = $(this);
					$('#project_id_deassign').val(prm_id);
					modal.find('.modal-title').text("Deassign " + prm_value + " from Sprint");
				},
				error: function(data) {
					alert('error');
				}
			});
			// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
			// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			//Set up the modal
			
			// modal.find('.modal-body').html("Are you sure you want to remove <strong>" + prm_value + "</strong> from the Project Request Manager? <br><br> <small class='text-muted'>THIS ACTION IS PERMANENT. DATA WILL NOT BE RECOVERED. (okay, that's a lie, it's still in the DB somewhere...)</small>");
		})


	$('#markComplete').on('show.bs.modal', function (event) {
		//Get all the data
		var button = $(event.relatedTarget) // Button that triggered the modal
		var type = button.data('prmtype') // Extract info from data-* attributes
		var prm_value = button.data('prmval') // Extract info from data-* attributes
		var prm_id = button.data('prmid')
		//Grab the AJAX Data we need via AjaxController
		$.ajax({
			context: $(this),
			url: '//tsprojects.pugetsound.edu/request/' + prm_id + '/ajax/status',
			type: 'POST',
			success: function(data) {
				var modal = $(this);
				$('#project_id_complete').val(prm_id);
				modal.find('.modal-title').text('Mark Project As Complete: ' + prm_value);
			},
			error: function(data) {
				alert('error');
			}
		});
		// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		//Set up the modal
		
		// modal.find('.modal-body').html("Are you sure you want to remove <strong>" + prm_value + "</strong> from the Project Request Manager? <br><br> <small class='text-muted'>THIS ACTION IS PERMANENT. DATA WILL NOT BE RECOVERED. (okay, that's a lie, it's still in the DB somewhere...)</small>");
	})


	$('#filterField').on('keyup', function() {
	    var rex = new RegExp($(this).val(), 'i');
	    $('.projects_searchable tr').hide();
	        $('.projects_searchable tr').filter(function() {
	            return rex.test($(this).text());
	        }).show();
	});

	$('#filterUsers').on('keyup', function() {
	    var rex = new RegExp($(this).val(), 'i');
	    $('.users_searchable tr').hide();
	        $('.users_searchable tr').filter(function() {
	            return rex.test($(this).text());
	        }).show();
	});


	var fixHelperModified = function(e, tr) {
		var $originals = tr.children();
		var $helper = tr.clone();
		$helper.children().each(function(index) {
		    $(this).width($originals.eq(index).width())
		});
		    return $helper;     
		};

	$('#reorder_table tbody').sortable({
		helper: fixHelperModified,
		cancel: ".sort_not_avail",
		stop: function(event, ui) {renumber_table('#reorder_table')}
	}).disableSelection();

});

function renumber_table(tableID) {
	$(tableID + " tr").each(function() {
		count = $(this).parent().children().index($(this)) + 1;
		$(this).find('.priority input.priority').val(count);
	});
}

function checkNotifications() {
  		$.ajax({
	    context: $(this),
	    url: '//tsprojects.pugetsound.edu/user/ajax/notifications',
	    type: 'POST',
	    success: function(data) {
	    	if (data.notif_num > 0) {
	    		$("#notifcount").animate({"background-color":"red"}, 500);
	    		$("#notifcount").html(data.notif_num);
	    	}
	    },
	    error: function(data) {
	    	console.log("Error while retrieving number of notifications")
	    }
	   });
  	}