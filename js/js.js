/**
 * @since 1.0
 * @link $URL$
 * @author $Author$
 * @version $Revision$
 * @Last Modified$
 * 
 */
//js.js
//Reads csv using Ajax call and pass data back to index.php


$(document).ready(function() {

	var sortByOrder = '';
	
	//When page loads get the data from csv ordered by name    
	sortBy('name','ASC');

	$('#sortByLastname').click( function() {

		sortByHelper (this.value,this.id,'lastName');

	}); 

	$('#sortByHeight').click( function() {

		sortByHelper (this.value,this.id,'height');

	});

	$('#sortByGender').click( function() {
		sortByHelper (this.value,this.id,'gender');
	});

	$('#sortByDOB').click( function() {

		sortByHelper (this.value,this.id,'dob');
	});
	
	/**
	 * sortByHelper
	 * @param val , value of the coulmn
	 * @param id , id of the column
	 * @param column , column name
	 * @returns sorted Order Object
	 */

	function sortByHelper (val,id,column) {

		var sortByOrder = '';
		if( val == 'ASC') {
			sortByOrder = 'ASC';
			$('#'+id).val('DSC');
		} else {
			sortByOrder = 'DSC';
			$('#'+id).val('ASC');
		}
		$('#displayOrder').text(column + '(' + sortByOrder + ')');
		sortBy(column, sortByOrder );

	}
	
	/**
	 * sortBy
	 * @param sorByColumn
	 * @param sortByOrder 
	 * @returns sorted Object and sends back to index.php
	 */

	function sortBy (sorByColumn,sortByOrder) {

		$.ajax({
			url: 'request.php',
			dataType: 'json',
			async: false,
			data: { 'sorByColumn' : sorByColumn,'sortByOrder' :sortByOrder },
			beforeSend: function() {
				$('#datBind').html('<tr><td colspan="5" class="noData"><img src="images/ajax-loader.gif" alt="Loading..." /></td><tr>');
			},
			success: function( data, textStatus ) {
				if( data.status == "OK" ) {
					console.log( "Status OK ");
					var line='';

					//Check for data and then proceed
					if( data.people != null) {
						var tempData = [];
						var i=1;
						$.each( data.people, function(  key,item ) {
							//console.log(key);

							line += '<tr><td>'+i+'</td>';
							line +='<td>'+item['name']+'</td>';
							line +='<td>'+item['height']+'</td>';
							line +='<td>'+item['gender']+'</td>';
							line +='<td>' + item['dobDisplay']+'</td>';

							line += '</tr>';

							//TODO: Store in window Object and then for client side sorting if needed
							/*
    							if(typeof(data.people)!=="undefined")
    							  {
    								tempData.push( item );
        							tempData.sort();
        							localStorage.setItem( 'peopleData', JSON.stringify(tempData));
    							  }
    							else
    							  {
    							  	// Sorry! No web storage support..
    								alert('Sorry, your browser does not support loading Materials in local storage');
    							  }

							 */
							i++;

						});
					} else {
						line += '<tr><td colspan="5" class="noData">No Data found</td></tr>';
					}
					$('#datBind').html(line);

				} else {
					console.log( "Error occured! " + data  );
				}

			},
			error: HandleAJAXError(),
			fail: function( xhdr, textStatus, errorThrown ) {
				console.log(textStatus + errorThrown );
			},
			complete: function( xhdr, textStatus ) {
				console.log(  textStatus );
			}
		});
	}
	
	/**
	 * HandleAJAXError
	 * @param xhdr
	 * @param textStatus 
	 * @param errorThrown
	 * @returns alert user if any Ajax errors
	 */
	function HandleAJAXError( xhdr, textStatus, errorThrown )
	{
		// this gets called for any ajax event??
		switch( errorThrown ) {
		case "timeout":
			alert( "Timeout connecting to the nlyte server. Please try again." );
			break;
		case "error":
			alert( "General error: "+errorThrown );
			break;

		case "abort":
			alert( "The action was aborted. Please try again." );
			break;

		case "parsererror":
			alert( "Parser error. Please try again." );
			break;
		}
	}


});


