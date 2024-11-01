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
	
if(typeof tour_detail_page == 'undefined')
	{
	var table_tmp_obj;
	jQuery(document).ready(function($){
		var search_str = ''
		
		if(enc_club_id != "")
			search_str = "enc_club_id="+enc_club_id;
		if(search_str != '')
			search_str = '?' + search_str;
			
	    var table_tmp_obj = $('#adminlist').DataTable({
	        pageLength: 25,
	        "processing": true,
	        "serverSide": true,
	        responsive: true,
	        "ajax":{
	        	data : function(d) {
		            d.action = 'tennisthor_tournaments_hook';
		            d.searching = get_search($('#adminlist'));
		        },
	        	url: tennisthor_wpadmin_url+"admin-post.php",
	        	"type": "POST",
	       	},        
	        "order": [[4, "desc" ]],
	        "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 7,11 ] },{ "searchable": false, "aTargets": [ 11 ] } ],        
	        "language": get_datatable_language_strings(),
	        initComplete: function () { 
				$('#adminlist').attr('style','width:100%;');
	        },        
	    });
	    
	    //code to add search box for all columns
	    $('#adminlist tfoot th').each( function () {    	
	    
	    	if($(this).index() == '11')
	    	{
		        var title = $(this).text();
		        $(this).html('');						
			}
	    	else
	    	{
		        var title = $(this).text();
		        $(this).html( '<input type="text" class="form-control form-control-clubsite" name="column['+$(this).index()+']" placeholder="" />' );			
			}
			
	    } );    
		
		//search code for list
		table_tmp_obj.columns().every(function() {
		    var that = this;
		    $('input', this.footer()).on('keyup', function(e) {
		        that.search(this.value).draw();
		    });
		    $('select', this.footer()).on('change', function(e) {
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

}
else
{
	jQuery(document).ready(function(){
	    jQuery('#adminlist_players').DataTable({
	        pageLength: 50,
	        responsive: true,
	        "order": [[5, "desc" ],[2, "asc" ]],
	        "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 0 ] },{ "searchable": false, "aTargets": [ 0 ] } ],
	        "language": get_datatable_language_strings(),
	    });	    
	    
	});		
}