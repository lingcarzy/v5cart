<?php
class ControllerFeedGoogleSitemap extends Controller {
   public function index() {
	  global $CATEGORIES;
	  
	  if (C('google_sitemap_status')) {
		 $output  = '<?xml version="1.0" encoding="UTF-8"?>';
		 $output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		 
		 $product_query = $this->db->query("SELECT link FROM @@product");
		 
		 foreach ($product_query->rows as $product) {
			$output .= '<url>';
			$output .= '<loc>' . HTTP_SERVER . $product['link'] . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>1.0</priority>';
			$output .= '</url>';
		 }
		 
		 foreach ($CATEGORIES as $k => $cate) {
			if ($k !== 'p') {
				$output .= '<url>';
				$output .= '<loc>' . HTTP_SERVER . $cate['link'] . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>1.0</priority>';
				$output .= '</url>';
			}
		 }
		 
		 M('catalog/manufacturer');
		 
		 $manufacturers = $this->model_catalog_manufacturer->getManufacturers();
		 
		 foreach ($manufacturers as $manufacturer) {
			$output .= '<url>';
			$output .= '<loc>' . U('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.7</priority>';
			$output .= '</url>';     
		 }
		 
		 M('catalog/page');
		 
		 $page_query = $this->db->query("SELECT link FROM @@page");
		 
		 foreach ($page_query->rows as $page) {
			$output .= '<url>';
			$output .= '<loc>' . HTTP_SERVER. $page['link'] . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.5</priority>';
			$output .= '</url>';   
		 }
		 
		 $output .= '</urlset>';
		 
		 $this->response->addHeader('Content-Type: application/xml');
		 $this->response->setOutput($output);
	  }
   }
}
?>