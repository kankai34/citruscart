<?php

/*------------------------------------------------------------------------
# com_citruscart
# ------------------------------------------------------------------------
# author   Citruscart Team  - Citruscart http://www.citruscart.com
# copyright Copyright (C) 2014 Citruscart.com All Rights Reserved.
# license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://citruscart.com
# Technical Support:  Forum - http://citruscart.com/forum/index.html
-------------------------------------------------------------------------*/
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

JHtml::_('script', 'media/citruscart/js/citruscart.js', false, false);
$items = $this->product_relations_data->items;
$products_model = Citruscart::getClass('CitruscartModelProducts', 'models.products');
?>

   <div id="product_relations">
		<h4><?php echo JText::_('COM_CITRUSCART_YOU_MAY_ALSO_BE_INTERESTED_IN'); ?></h4>
        <?php $k = 0; ?>
        <ul class="citruscart_products_containers">
          	     <?php foreach ($items as $item): ?>
          	            <li class="citruscart_product_relation_lists">
	    	    			<a href="<?php echo JRoute::_( 'index.php?option=com_citruscart&view=products&task=view&id='.$item->product_id . '&Itemid=' . $products_model->getItemid( $item->product_id ) ); ?>">
	                   				<?php echo CitruscartHelperProduct::getImage($item->product_id, 'id', $item->product_name, 'full', false, false, array( 'width'=>80 ,'height'=>45 ) ); ?>
	                    		</a>
	                    		<br/>
	         					<a href="<?php echo JRoute::_( 'index.php?option=com_citruscart&view=products&task=view&id='.$item->product_id . '&Itemid=' . $products_model->getItemid( $item->product_id ) ); ?>">
		                    		<?php echo $newtext = substr($item->product_name,0,15);	?>
	                    		</a>
								<br/>
								<strong>
	                     			<?php echo CitruscartHelperProduct::dispayPriceWithTax($item->product_price, $item->tax, $this->product_relations_data->show_tax); ?>
	                    		</strong>

	                     <?php $k = 1 - $k; ?>
	       		 <?php endforeach; ?>
       		</span>
			</li>
			</ul>
        <div class="reset"></div>
    </div>
	<div class="reset"></div>
