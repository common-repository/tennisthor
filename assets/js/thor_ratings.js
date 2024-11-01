var globalLang = [];

globalLang['j_tab_txt1'] = j_tab_txt1;
globalLang['j_tab_txt2'] = j_tab_txt2;
globalLang['j_tab_txt3'] = j_tab_txt3;
globalLang['j_tab_txt4'] = j_tab_txt4;
globalLang['j_tab_txt5'] = j_tab_txt5;
globalLang['j_tab_txt6'] = j_tab_txt6;
globalLang['j_tab_txt7'] = j_tab_txt7;
globalLang['j_tab_txt8'] = j_tab_txt8;
globalLang['j_tab_txt9'] = j_tab_txt9;
globalLang['j_tab_txt10'] = j_tab_txt10;
globalLang['j_tab_txt11'] = j_tab_txt11;
globalLang['j_tab_txt12'] = j_tab_txt12;
globalLang['j_tab_txt13'] = j_tab_txt13;
globalLang['j_tab_txt14'] = j_tab_txt14;

function get_datatable_language_strings()
{
	return {
		    "emptyTable": globalLang['j_tab_txt1'],
			"lengthMenu": globalLang['j_tab_txt2'],
			"info": globalLang['j_tab_txt3'],
			"infoEmpty": globalLang['j_tab_txt4'],
			"infoFiltered": globalLang['j_tab_txt5'],
			"loadingRecords": globalLang['j_tab_txt6'],
			"processing": globalLang['j_tab_txt7'],
			"search": globalLang['j_tab_txt8'],
			"emptyTable": globalLang['j_tab_txt9'],
			"zeroRecords": globalLang['j_tab_txt10'],
			"paginate": {
			        "first":      globalLang['j_tab_txt11'],
			        "last":       globalLang['j_tab_txt12'],
			        "next":       globalLang['j_tab_txt13'],
			        "previous":   globalLang['j_tab_txt14'],
			    },			
	    };	
}

jQuery(document).ready(function(){
    var table_tmp_obj = jQuery('#adminlist').DataTable({
        pageLength: 25,
        responsive: true,
        "order": [[5, "asc" ]],
        "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 0 ] } ],
        "language": get_datatable_language_strings(),
    });

    //code to add search box for all columns
    jQuery('#adminlist tfoot th').each( function () {    	
    	if(jQuery(this).index() == '0' )
    	{
	        var title = jQuery(this).text();
	        jQuery(this).html('');						
		}
    	else
    	{
	        var title = jQuery(this).text();
	        jQuery(this).html( '<input type="text" class="form-control" name="column['+jQuery(this).index()+']" placeholder="" />' );			
		}
    } );     

    

	//search code for list
	table_tmp_obj.columns().every(function() {
	    var that = this;
	    jQuery('input', this.footer()).on('keyup', function(e) {
	        that.search(this.value).draw();
	    });

	    jQuery('select', this.footer()).on('change', function(e) {
	        that.search(this.value).draw();
	    });
	});      
});



function get_search(datatable) {
    var result = [];
    datatable.find('tfoot').find('input, select').each(function() {
        result.push([jQuery(this).attr('name'), jQuery(this).val()]);
    });
    return result;
}

