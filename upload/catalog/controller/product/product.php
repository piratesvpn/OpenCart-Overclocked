<?php
class ControllerProductProduct extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('product/product');

		// Breadcrumbs
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'		=> $this->language->get('text_home'),
			'href'		=> $this->url->link('common/home'),
			'separator' => false
		);

		$this->load->model('catalog/category');

		if (isset($this->request->get['path']) && !is_array($this->request->get['path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$this->data['breadcrumbs'][] = array(
						'text'		=> $category_info['name'],
						'href'		=> $this->url->link('product/category', 'path=' . $path . $url),
						'separator' => $this->language->get('text_separator')
					);
				}
			}

			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$this->data['breadcrumbs'][] = array(
					'text'		=> $category_info['name'],
					'href'		=> $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url),
					'separator' => $this->language->get('text_separator')
				);
			}
		}

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->get['manufacturer_id'])) {
			$this->data['breadcrumbs'][] = array(
				'text'		=> $this->language->get('text_brand'),
				'href'		=> $this->url->link('product/manufacturer'),
				'separator' => $this->language->get('text_separator')
			);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {
				$this->data['breadcrumbs'][] = array(
					'text'		=> $manufacturer_info['name'],
					'href'		=> $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url),
					'separator' => $this->language->get('text_separator')
				);
			}
		}

		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'		=> $this->language->get('text_search'),
				'href'		=> $this->url->link('product/search', $url),
				'separator' => $this->language->get('text_separator')
			);
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			} 

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'		=> $product_info['name'],
				'href'		=> $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id']),
				'separator' => $this->language->get('text_separator')
			);

			$this->document->setTitle($product_info['name']);
			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');

			$this->document->addScript('catalog/view/javascript/jquery/tabs.js');

			$this->data['heading_title'] = $product_info['name'];

			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_offer'] = $this->language->get('text_offer');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_model'] = $this->language->get('text_model');
			$this->data['text_reward'] = $this->language->get('text_reward');
			$this->data['text_points'] = $this->language->get('text_points');
			$this->data['text_discount'] = $this->language->get('text_discount');
			$this->data['text_location'] = $this->language->get('text_location');
			$this->data['text_stock'] = $this->language->get('text_stock');
			$this->data['text_price'] = $this->language->get('text_price');
			$this->data['text_tax'] = $this->language->get('text_tax');
			$this->data['text_option'] = $this->language->get('text_option');
			$this->data['text_qty'] = $this->language->get('text_qty');
			$this->data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$this->data['text_or'] = $this->language->get('text_or');
			$this->data['text_write'] = $this->language->get('text_write');
			$this->data['text_note'] = $this->language->get('text_note');
			$this->data['text_share'] = $this->language->get('text_share');
			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_tags'] = $this->language->get('text_tags');

			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_review'] = $this->language->get('entry_review');
			$this->data['entry_rating'] = $this->language->get('entry_rating');
			$this->data['entry_good'] = $this->language->get('entry_good');
			$this->data['entry_bad'] = $this->language->get('entry_bad');
			$this->data['entry_captcha'] = $this->language->get('entry_captcha');

			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_quote'] = $this->language->get('button_quote');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');
			$this->data['button_upload'] = $this->language->get('button_upload');
			$this->data['button_continue'] = $this->language->get('button_continue');

			$this->load->model('catalog/review');

			$this->data['tab_description'] = $this->language->get('tab_description');
			$this->data['tab_attribute'] = $this->language->get('tab_attribute');
			$this->data['tab_offer'] = $this->language->get('tab_offer');
			$this->data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);
			$this->data['tab_related'] = $this->language->get('tab_related');

			$this->data['lang'] = $this->language->get('code');

			$this->data['product_id'] = (int)$this->request->get['product_id'];

			$this->load->model('tool/image');

			if ($this->config->get('config_lightbox') == 'zoomlens') {
				$this->document->addStyle('catalog/view/javascript/jquery/simple-lens/jquery.simpleLens.css');
				$this->document->addScript('catalog/view/javascript/jquery/simple-lens/jquery.simpleGallery.min.js');
				$this->document->addScript('catalog/view/javascript/jquery/simple-lens/jquery.simpleLens.min.js');

				if ($product_info['image']) {
					$this->data['zoom'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width') * 2, $this->config->get('config_image_popup_height') * 2);
					$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], 230, 230);
					$this->data['gallery_thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
					$this->data['column_offset'] = 265;
				} else {
					$this->data['zoom'] = '';
					$this->data['thumb'] = '';
					$this->data['gallery_thumb'] = '';
					$this->data['column_offset'] = 0;
				}

				$this->data['lightbox'] = 'zoomlens';

			} elseif ($this->config->get('config_lightbox') == 'magnific') {
				$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific.css');
				$this->document->addScript('catalog/view/javascript/jquery/magnific/magnific.min.js');

				if ($product_info['image']) {
					$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
					$this->data['column_offset'] = $this->config->get('config_image_thumb_width') + 35;
				} else {
					$this->data['thumb'] = '';
					$this->data['column_offset'] = 0;
				}

				$this->data['lightbox'] = 'magnific';

			} elseif ($this->config->get('config_lightbox') == 'chocolat') {
				$this->document->addStyle('catalog/view/javascript/jquery/chocolat/css/chocolat.css');
				$this->document->addScript('catalog/view/javascript/jquery/chocolat/js/jquery.chocolat.min.js');

				if ($product_info['image']) {
					$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
					$this->data['column_offset'] = $this->config->get('config_image_thumb_width') + 35;
				} else {
					$this->data['thumb'] = '';
					$this->data['column_offset'] = 0;
				}

				$this->data['lightbox'] = 'chocolat';

			} else {
				$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
				$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');

				if ($product_info['image']) {
					$this->data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
				} else {
					$this->data['popup'] = '';
				}

				if ($product_info['image']) {
					$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
					$this->data['column_offset'] = $this->config->get('config_image_thumb_width') + 35;
				} else {
					$this->data['thumb'] = '';
					$this->data['column_offset'] = 0;
				}

				$this->data['lightbox'] = 'colorbox';
			}

			if ($product_info['image']) {
				$this->data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			} else {
				$this->data['popup'] = '';
			}

			$this->data['images'] = array();

			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

			foreach ($results as $result) {
				$this->data['images'][] = array(
					'zoom' 	=> $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width') * 2, $this->config->get('config_image_popup_height') * 2),
					'popup'	=> $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'thumb' 	=> $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
				);
			}

			$this->data['manufacturer'] = $product_info['manufacturer'];
			$this->data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$this->data['model'] = $product_info['model'];
			$this->data['reward'] = $product_info['reward'];
			$this->data['points'] = $product_info['points'];

			if ($product_info['quantity'] <= 0) {
				$this->data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$this->data['stock'] = $product_info['quantity'];
			} else {
				$this->data['stock'] = $this->language->get('text_instock');
			}

			$this->load->model('localisation/location');

			$this->data['locations'] = array();

			$location_results = $this->model_catalog_product->getProductLocationId($this->request->get['product_id']);

			arsort($location_results);

			foreach ($location_results as $location_result) {
				if ($location_result > 0) {
					$this->data['locations'][] = $this->model_localisation_location->getLocation($location_result);
				}
			}

			$this->load->model('design/palette');

			$this->data['colors'] = array();

			if ($product_info['palette_id'] > 0) {
				$colors = $this->model_design_palette->getPaletteColors($product_info['palette_id']);

				if ($colors) {
					foreach ($colors as $color) {
						$this->data['colors'][] = array(
							'title'		=> $color['title'],
							'color'	=> $color['color']
						);
					}
				}
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				if (($product_info['price'] == '0.0000') && $this->config->get('config_price_free')) {
					$this->data['price'] = $this->language->get('text_free');
				} else {
					$this->data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				}
			} else {
				$this->data['price'] = false;
			}

			if ((float)$product_info['special']) {
				$this->data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['special'] = false;
			}

			if ($this->config->get('config_tax')) {
				$this->data['tax'] = $this->currency->format((float)$product_info['special']) ? $product_info['special'] : $product_info['price'];
			} else {
				$this->data['tax'] = false;
			}

			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

			$this->data['discounts'] = array();

			foreach ($discounts as $discount) {
				$this->data['discounts'][] = array(
					'quantity' 	=> $discount['quantity'],
					'price'    	=> $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
				);
			}

			if ($product_info['quote']) {
				$this->data['is_quote'] = $this->url->link('information/contact');
			} else {
				$this->data['is_quote'] = false;
			}

			$this->data['options'] = array();

			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
					$option_value_data = array();

					foreach ($option['option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
								$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false));
							} else {
								$price = false;
							}

							$option_value_data[] = array(
								'product_option_value_id'	=> $option_value['product_option_value_id'],
								'option_value_id'         		=> $option_value['option_value_id'],
								'name'                    		=> $option_value['name'],
								'image'                   		=> $this->model_tool_image->resize($option_value['image'], 50, 50),
								'price'                   			=> $price,
								'price_prefix'            		=> $option_value['price_prefix']
							);
						}
					}

					$this->data['options'][] = array(
						'product_option_id' 	=> $option['product_option_id'],
						'option_id'         		=> $option['option_id'],
						'name'              		=> $option['name'],
						'type'              		=> $option['type'],
						'option_value'      	=> $option_value_data,
						'required'          		=> $option['required']
					);

				} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$this->data['options'][] = array(
						'product_option_id' 	=> $option['product_option_id'],
						'option_id'         		=> $option['option_id'],
						'name'              		=> $option['name'],
						'type'              		=> $option['type'],
						'option_value'      	=> $option['option_value'],
						'required'          		=> $option['required']
					);
				}
			}

			if ($product_info['minimum']) {
				$this->data['minimum'] = $product_info['minimum'];
			} else {
				$this->data['minimum'] = 1;
			}

			if ($this->config->get('config_addthis')) {
				$this->data['addthis'] = $this->config->get('config_addthis');
			} else {
				$this->data['addthis'] = false;
			}

			$this->data['label'] = $this->config->get('config_offer_label');

			$this->load->model('catalog/offer');

			$this->data['offers'] = array();

			$product_offers = $this->model_catalog_offer->getOfferProducts($this->request->get['product_id']);

			if ($product_offers) {
				foreach ($product_offers as $product_offer) {
					if ($product_offer['one'] == $this->request->get['product_id']) {
						$product_offer_image = $this->model_catalog_offer->getOfferProductImage($product_offer['two']);

						if ($product_offer_image) {
							$offer_image = $this->model_tool_image->resize($product_offer_image, $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
						} else {
							$offer_image = false;
						}

						$offer_name = $this->model_catalog_offer->getOfferProductName($product_offer['two']);
						$offer_mirror_name = $this->model_catalog_offer->getOfferProductName($product_offer['one']);

						$offer_product = $product_offer['two'];

					} elseif ($product_offer['two'] == $this->request->get['product_id']) {
						$product_offer_image = $this->model_catalog_offer->getOfferProductImage($product_offer['one']);

						if ($product_offer_image) {
							$offer_image = $this->model_tool_image->resize($product_offer_image, $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
						} else {
							$offer_image = false;
						}

						$offer_name = $this->model_catalog_offer->getOfferProductName($product_offer['one']);
						$offer_mirror_name = $this->model_catalog_offer->getOfferProductName($product_offer['two']);

						$offer_product = $product_offer['one'];

					} else {
						$offer_image = false;
						$offer_name = '';
						$offer_mirror_name = '';
						$offer_product = '';
					}

					if ($product_offer['group'] == 'G241') {
						$offer_label = sprintf($this->language->get('text_G241'), $product_offer['type']);
					} elseif ($product_offer['group'] == 'G241D') {
						$offer_label = sprintf($this->language->get('text_G241D'), $offer_mirror_name, $offer_name, $product_offer['type']);
					} elseif ($product_offer['group'] == 'G242D') {
						$offer_label = sprintf($this->language->get('text_G242D'), $offer_mirror_name, $offer_name, $product_offer['type']);
					} elseif ($product_offer['group'] == 'G142D') {
						$offer_label = sprintf($this->language->get('text_G142D'), $product_offer['type'], $offer_mirror_name, $offer_name);
					} else {
						$offer_label = '';
					}

					$this->data['offers'][] = array(
						'thumb'	=> $offer_image,
						'name'	=> $offer_name,
						'href'		=> $this->url->link('product/product', 'product_id=' . $offer_product),
						'group'	=> $offer_label
					);
				}
			}

			$this->data['review_status'] = $this->config->get('config_review_status');
			$this->data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
			$this->data['rating'] = (int)$product_info['rating'];
			$this->data['captcha'] = ''; // ReCaptcha required
			$this->data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			$this->data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

			$related_offers = $this->model_catalog_offer->getListProductOffers(0);

			$this->data['products'] = array();

			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} else {
					$image = false;
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					if (($result['price'] == '0.0000') && $this->config->get('config_price_free')) {
						$price = $this->language->get('text_free');
					} else {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
					}
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				if (in_array($result['product_id'], $related_offers, true)) {
					$offer = true;
				} else {
					$offer = false;
				}

				$this->data['products'][] = array(
					'product_id'	=> $result['product_id'],
					'thumb'  		=> $image,
					'offer'			=> $offer,
					'name'    	=> $result['name'],
					'price'   	 	=> $price,
					'special' 	 	=> $special,
					'rating'    	=> $rating,
					'reviews'    	=> sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'    	 	=> $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			$this->data['tags'] = array();

			if ($product_info['tag']) {
				$tags = explode(',', $product_info['tag']);

				foreach ($tags as $tag) {
					$this->data['tags'][] = array(
						'tag'	=> trim($tag),
						'href' 	=> $this->url->link('product/search', 'tag=' . trim($tag))
					);
				}
			}

			if ($this->customer->isLogged()) {
				$this->data['text_payment_profile'] = $this->language->get('text_payment_profile');

				$this->data['profiles'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);
			} else {
				$this->data['profiles'] = false;
			}

			$this->model_catalog_product->updateViewed($this->request->get['product_id']);

			// Theme
			$this->data['template'] = $this->config->get('config_template');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/product.tpl';
			} else {
				$this->template = 'default/template/product/product.tpl';
			}

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_header',
				'common/content_top',
				'common/content_bottom',
				'common/content_footer',
				'common/footer',
				'common/header'
			);

			$this->response->setOutput($this->render());

		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'  	=> $this->language->get('text_error'),
				'href'   	=> $this->url->link('product/product', $url . '&product_id=' . $product_id),
				'separator' => $this->language->get('text_separator')
			);

			$this->data['heading_title'] = $this->language->get('text_error');

			$this->data['text_error'] = $this->language->get('text_error');

			$this->data['button_continue'] = $this->language->get('button_continue');

			$this->data['continue'] = $this->url->link('common/home');

			// Theme
			$this->data['template'] = $this->config->get('config_template');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_header',
				'common/content_top',
				'common/content_bottom',
				'common/content_footer',
				'common/footer',
				'common/header'
			);

			$this->response->addheader($this->request->server['SERVER_PROTOCOL'] . ' 404 not found');
			$this->response->setOutput($this->render());
		}
	}

	public function review() {
		$this->language->load('product/product');

		// Review
		$this->load->model('catalog/review');

		$this->data['text_latest'] = $this->language->get('text_latest');
		$this->data['text_on'] = $this->language->get('text_on');
		$this->data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->data['reviews'] = array();

		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 3, 3);

		foreach ($results as $result) {
			$this->data['reviews'][] = array(
				'author'     		=> $result['author'],
				'text'       		=> nl2br($result['text']),
				'rating'     		=> (int)$result['rating'],
				'reviews'    		=> sprintf($this->language->get('text_reviews'), (int)$review_total),
				'date_added' 	=> date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 3;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		// Theme
		$this->data['template'] = $this->config->get('config_template');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/review.tpl';
		} else {
			$this->template = 'default/template/product/review.tpl';
		}

		$this->response->setOutput($this->render());
	}

	public function getRecurringDescription() {
		$this->language->load('product/product');

		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = $this->request->get['product_id'];
		}

		if (isset($this->request->post['profile_id'])) {
			$profile_id = $this->request->post['profile_id'];
		} elseif (isset($this->request->get['profile_id'])) {
			$profile_id = $this->request->get['profile_id'];
		} else {
			$profile_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);
		$profile_info = $this->model_catalog_product->getProfile($product_id, $profile_id);

		$json = array();

		if ($product_info && $profile_info && !$json) {
			$frequencies = array(
				'day' 				=> $this->language->get('text_day'),
				'week' 			=> $this->language->get('text_week'),
				'semi_month' 	=> $this->language->get('text_semi_month'),
				'month' 			=> $this->language->get('text_month'),
				'year' 			=> $this->language->get('text_year')
			);

			foreach ($profile_info as $result) {
				if ($result['trial_status'] == 1) {
					$price = $this->currency->format($this->tax->calculate($result['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')));

					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $result['trial_cycle'], $frequencies[$result['trial_frequency']], $result['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($result['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')));

				if ($result['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $result['cycle'], $frequencies[$result['frequency']], $result['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_until_canceled_description'), $price, $result['cycle'], $frequencies[$result['frequency']], $result['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function write() {
		$this->language->load('product/product');

		$this->load->model('catalog/review');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->config->get('config_review_status')) {
			if (empty($this->request->post['name']) || (utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if (empty($this->request->post['text']) || (utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating'])) {
				$json['error'] = $this->language->get('error_rating');
			}

			if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != strtolower($this->request->post['captcha']))) {
				$json['error'] = $this->language->get('error_captcha');
			}

			if (!isset($json['error'])) {
				unset($this->session->data['captcha']);

				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function captcha() {
		$this->load->library('captcha');

		$font = $this->config->get('config_captcha_font');

		$captcha = new Captcha();

		$this->session->data['captcha'] = $captcha->getCode();

		$captcha->showImage($font);
	}

	public function upload() {
		$this->language->load('product/product');

		$json = array();

		if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));

			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
				$json['error'] = $this->language->get('error_filename');
			}

			// Allowed file extension types
			$allowed = array();

			$filetypes = explode("\n", str_replace(array("\r\n", "\r"), "\n", $this->config->get('config_file_extension_allowed')));

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Allowed file mime types
			$allowed = array();

			$filetypes = explode("\n", str_replace(array("\r\n", "\r"), "\n", $this->config->get('config_file_mime_allowed')));

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array($this->request->files['file']['type'], $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Check to see if any PHP files are trying to be uploaded
 			$content = file_get_contents($this->request->files['file']['tmp_name']);

			if (preg_match('/\<\?php/i', $content)) {
				$json['error'] = $this->language->get('error_filetype');
 			}

			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}

		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!$json && is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
			$file = basename($filename) . '.' . hash_rand('md5');

			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $file);

			// Hide the uploaded file name so people can not link to it directly.
			$this->load->model('tool/upload');

			$json['code'] = $this->model_tool_upload->addUpload($filename, $file);

			$json['success'] = $this->language->get('text_upload');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
?>