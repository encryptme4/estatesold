<!DOCTYPE HTML>
<html>
<head>
<?php $this->load->view('common/head'); ?>
<link href="<?php echo base_url('assets/front'); ?>/js/sweetalert.css" rel="stylesheet" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/front/css/jquery.qtip.min.css'); ?>">
</head>
<body>
<!-- Header section start here  -->
<?php $this->load->view('common/header2'); ?>
<!-- Header section end here  --> 

<!-- Banner section start here  -->
 <div class="topTxt">
  <div class="wrapper">
    <h1>WELCOME HOME</h1>
    <p>The Registered User Report lists all of the registered bidders who have bid on your auction</p>
  </div>
 </div>
<!-- Banner section start here  --> 

<!-- Middle section start here  -->
<div class="middle">

 <div class="sellerDash">
   <div class="wrapper">
     <div class="dashLeft">
       <div class="dashNav">
       <?php $this->load->view('common/reports-menu'); ?>
     </div>
      </div>
      
     <div class="dashRight">
	 <div class="dshRecent">
	 		<h2>Bidder Report</h2>
		</div>
        <form name="frm_search" id="frm_search" action="" method="get">
           <div class="slritmDiv2">
             
			 
             <!--<div class="srcFor"><input type="text" name="s" value="<?php echo $this->input->get('s'); ?>" placeholder="Search for.."><input type="submit" value=""></div>
             <select name="sortBy" onChange="javascript:document.frm_search.submit()">
			 	<option value="">Sort By</option>
				<option value="user_name" <?php echo (isset($sortBy) && $sortBy=="user_name") ? 'selected="selected"' : ''; ?>>Bidder Name</option>
				<option value="email_address" <?php echo (isset($sortBy) && $sortBy=="email_address") ? 'selected="selected"' : ''; ?>>Bidder Email</option>
			</select>-->

        <select name="sale_id" onChange="javascript:document.frm_search.submit();" style="width:50%">
              <option value="">Filter By Sales</option>
              <?php foreach($sale_ids as $sale):?>
                <option value="<?php echo $sale['id'];?>" <?php echo (isset($sale_id) && $sale_id==$sale['id']) ? 'selected' : ''; ?>><?php echo $sale['sale_title']?></option> 
              <?php endforeach;?> 
              
            </select>
          <div class="clr_exrep">
             <a style="float:left;padding-right: 10px;" href="<?php echo base_url('seller/reports/registered-users'); ?>" class="clr">Clear</a>
             <?php 
             if(isset($sale_id) && $sale_id!=""):
              $pdf_url = base_url('seller/reports/register_user_pdf?sale_id='.$sale_id.'');
             else:
             $pdf_url = "javascript:alert('No data found to create pdf!');";
             endif;
             ?>
             <a style="float:left;" href="<?php echo $pdf_url; ?>" class="exrep">Export Report</a> 
            </div> <br><br><br>
            <div class="srcFor"><input type="text" name="s" value="<?php echo $this->input->get('s');?> "
              placeholder="Search for.."><input type="submit" value=""></div>
           </div>
		   </form>
        <div class="slrBuyers">
           <div class="">
             <table class="display" id="data_tbl">
			 <thead>
              <tr>
                <th class="dt-head-left">Bidder Name</th>
                <th class="dt-head-left">Email</th>
                <th class="dt-head-left">Phone Number</th>
				        <th class="dt-head-left">Bids Placed</th>
                <th class="dt-head-left">Items Won</th>
                <th class="dt-head-left">Total Spend</th>
              </tr>
			  </thead>
			  <tbody>
              <?php if(is_array($reg_users) && count($reg_users)>0): 

			  		$ijk=0;
			  		foreach($reg_users as $buyer):
					$ijk++;
					$b_user = $this->user_model->find(array('id'=>$buyer['bidder_id']));
					$bid_placed = $this->bid_model->findCount(array('bidder_id'=>$buyer['bidder_id']));
          if(isset($sale_id) && $sale_id != ""){
            $bid_placed = $this->reports_model->findsalebid($buyer['bidder_id'],$sale_id);
					 $won_list_count = $this->bid_model->getWonListCount($buyer['bidder_id'],$buyer['item_id']);
            $item_won = $this->bid_model->getWonList($buyer['bidder_id'],$buyer['item_id']);
          }
          else{
             $won_list_count = $this->bid_model->getWonListCount($buyer['bidder_id']);
              $item_won = $this->bid_model->getWonList($buyer['bidder_id'],"");
          }
					if(is_array($b_user) && count($b_user)>0){
 
          $amount_spent = 0;
          if(!empty($item_won)){

           foreach($item_won as $wons){
            $amount_spent= $amount_spent+$wons['bid_amount'];
          } 
          }
          
			  ?>
			 
              <tr>
                <td><span class="theading">Bidder Name</span><?php echo $b_user['user_name']; ?></td>
                
                <td><span class="theading">Email</span><?php echo $b_user['email_address']; ?></td>
                <td class="tl"><span class="theading">Phone Number</span><?php echo $b_user['contact_number']; ?></td>
				<td><span class="theading">Bid Placed</span><?php echo $bid_placed; ?></td>
                <td>
					<span class="theading">Items Won</span>
					<a href="<?php echo base_url('seller/reports/item-won/'.$b_user['slug']); ?>"><?php echo $won_list_count; ?></a>
				</td>
        <td><?php echo CURRENCY.$amount_spent; ?></td>
              </tr>
			   
              <?php 
			  }
			  		endforeach;
				else:
			  ?>
			   <tr><td colspan="5">No record found</td> </tr>
			  <?php 
				endif;
			  ?>
			  
             </tbody>
              
             </table>
           
           </div>
          
        
        </div>
        
        
     </div>
   
   </div>
 </div>
  
</div>
<!-- Middle section end here  --> 
<!-- Footer section start here  -->
<?php $this->load->view('common/footer'); ?>
<!-- Footer section end here  -->
<script src="<?php echo base_url('assets/front/js'); ?>/sorttable.js"></script>
<script>
$(document).ready( function () {
  $('#data_tbl').DataTable({searching: false, paging: false, 'bInfo': false});
});
$(document).ready(function(){
	$(".sortable").find('th.thF').addClass('sorttable_sorted').trigger('click');
});
</script>
</body>
</html>
