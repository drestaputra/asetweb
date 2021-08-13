<link rel="stylesheet" type="text/css" href="https://www.grocerycrud.com/assets/grocery_crud/themes/bootstrap/css/general.css?version=v1.6.2">
<table cellpadding="0" cellspacing="0" border="0" class="display groceryCrudTable table table-striped table-bordered" id="<?php echo uniqid(); ?>">
	<thead>
		<tr>
			<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
			<th class='actions'><?php echo $this->l('list_actions'); ?></th>
			<?php }?>
			<?php foreach($columns as $column){?>
				<th><?php echo $column->display_as; ?></th>
			<?php }?>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $num_row => $row){ ?>
		<tr id='row-<?php echo $num_row?>'>
			<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
			<td class='actions'>
				<?php if(!empty($row->action_urls)){
					foreach($row->action_urls as $action_unique_id => $action_url){
						$action = $actions[$action_unique_id]; ?>
						<a href="<?php echo $action_url; ?>" class="edit_button btn btn-xs btn-info" role="button">
							<span class="glyphicon glyphicon-<?php echo $action->css_class; ?> <?php echo $action_unique_id;?>"></span>
							<?php echo $action->label?>
						</a>
					<?php }
				} ?>

				<?php if(!$unset_read){?>
					<a href="<?php echo $row->read_url?>" class="edit_button btn btn-xs btn-info" role="button">
						<span class="glyphicon glyphicon-info-sign"></span>
						<?php echo $this->l('list_view');?>
					</a>
				<?php }?>

                <?php if(!$unset_clone){?>
                    <a href="<?php echo $row->clone_url?>" class="edit_button btn btn-xs btn-info hidden-xs" role="button">
                        <span class="glyphicon glyphicon-duplicate"></span>
                        <?php echo $this->l('list_clone'); ?>
                    </a>
                <?php }?>

				<?php if(!$unset_edit){?>
					<a href="<?php echo $row->edit_url?>" class="edit_button btn btn-xs btn-info hidden-xs" role="button">
						<span class="glyphicon glyphicon-pencil"></span>
						<?php echo $this->l('list_edit'); ?>
					</a>
				<?php }?>

				<?php if(!$unset_delete){?>
					<a onclick = "javascript: return delete_row('<?php echo $row->delete_url?>', '<?php echo $num_row?>')"
						href="javascript:void(0)" class="delete_button btn btn-xs btn-danger hidden-xs" role="button">
						<span class="glyphicon glyphicon-trash"></span>
						<?php echo $this->l('list_delete'); ?>
					</a>
				<?php }?>
			</td>
			<?php }?>
			<?php foreach($columns as $column){?>
				<td><?php echo $row->{$column->field_name}?></td>
			<?php }?>
		</tr>
		<?php }?>
	</tbody>
	<tfoot>
		<tr>
			<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
				<th>
					<button class="btn btn-success refresh-data btn-block" role="button" data-url="<?php echo $ajax_list_url; ?>">
						<span class="glyphicon glyphicon-refresh"></span> Clear Filter
					</button>
				</th>
			<?php }?>
			<?php foreach($columns as $column){?>
				<th class="text-center"><input type="text" class="form-control filter-column-input" style="width:100%;" name="<?php echo $column->field_name; ?>" placeholder="<?php echo $this->l('list_search').' '.$column->display_as; ?>" class="search_<?php echo $column->field_name; ?>" /></th>
			<?php }?>
		</tr>
	</tfoot>
</table>
 <!-- Table Footer -->
        					<div class="footer-tools">

                                            <!-- "Show 10/25/50/100 entries" (dropdown per-page) -->
                                            <div class="floatL t20 l5">
                                                <div class="floatL t10">
                                                                                                        Show                                                 </div>
                                                <div class="floatL r5 l5 t3">
                                                    <select name="per_page" class="per_page form-control">
                                                                                                                    <option value="10"
                                                                    selected="selected">
                                                                        10&nbsp;&nbsp;
                                                            </option>
                                                                                                                    <option value="25"
                                                                    >
                                                                        25&nbsp;&nbsp;
                                                            </option>
                                                                                                                    <option value="50"
                                                                    >
                                                                        50&nbsp;&nbsp;
                                                            </option>
                                                                                                                    <option value="100"
                                                                    >
                                                                        100&nbsp;&nbsp;
                                                            </option>
                                                                                                            </select>
                                                </div>
                                                <div class="floatL t10">
                                                     entries                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                            <!-- End of "Show 10/25/50/100 entries" (dropdown per-page) -->


                                            <div class="floatR r5">

                                                <!-- Buttons - First,Previous,Next,Last Page -->
                                                <ul class="pagination">
                                                    <li class="disabled paging-first"><a href="#"><i class="fa fa-step-backward"></i></a></li>
                                                    <li class="prev disabled paging-previous"><a href="#"><i class="fa fa-chevron-left"></i></a></li>
                                                    <li>
                                                        <span class="page-number-input-container">
                                                            <input type="number" value="1" class="form-control page-number-input" />
                                                        </span>
                                                    </li>
                                                    <li class="next paging-next"><a href="#"><i class="fa fa-chevron-right"></i></a></li>
                                                    <li class="paging-last"><a href="#"><i class="fa fa-step-forward"></i></a></li>
                                                </ul>
                                                <!-- End of Buttons - First,Previous,Next,Last Page -->

                                                <input type="hidden" name="page_number" class="page-number-hidden" value="1" />

                                                <!-- Start of: Settings button -->
                                                <div class="btn-group floatR t20 l10 settings-button-container">
                                                    <button type="button" class="btn btn-default gc-bootstrap-dropdown settings-button dropdown-toggle">
                                                        <i class="fa fa-cog r5"></i>
                                                        <span class="caret"></span>
                                                    </button>

                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li>
                                                            <a href="javascript:void(0)" class="clear-filtering">
                                                                <i class="fa fa-eraser"></i> Clear filtering                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <!-- End of: Settings button -->

                                            </div>


                                            <!-- "Displaying 1 to 10 of 116 items" -->
                                            <div class="floatR r10 t30">
                                                Displaying <span class="paging-starts">1</span> to <span class="paging-ends">10</span> of <span class="current-total-results">124</span> items                                                <span class="full-total-container hidden">
                                                    (filtered from <span class='full-total'>124</span> total entries)                                                </span>
                                            </div>
                                            <!-- End of "Displaying 1 to 10 of 116 items" -->

                                            <div class="clear"></div>
                            </div>
                            <!-- End of: Table Footer -->
