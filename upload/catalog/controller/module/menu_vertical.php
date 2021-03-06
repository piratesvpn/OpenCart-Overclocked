<?php
class ControllerModuleMenuVertical extends Controller {
	private $_name = 'menu_vertical';

	protected function index($setting) {
		static $module = 0;

		$this->language->load('module/' . $this->_name);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['theme'] = $this->config->get($this->_name . '_theme');
		$this->data['title'] = $this->config->get($this->_name . '_title' . $this->config->get('config_language_id'));

		if (!$this->data['title']) {
			$this->data['title'] = $this->data['heading_title'];
		}

		// Stylesheet override
		$template = $this->config->get('config_template');

		$stylesheet_mode = $this->config->get($template . '_stylesheet');

		if (!$stylesheet_mode) {
			$header_color = $this->config->get($this->_name . '_header_color');
			$header_shape = $this->config->get($this->_name . '_header_shape');
			$content_color = $this->config->get($this->_name . '_content_color');
			$content_shape = $this->config->get($this->_name . '_content_shape');

			$this->data['header_color'] = ($header_color) ? $header_color . '-skin' : 'white-skin';
			$this->data['header_shape'] = ($header_shape) ? $header_shape . '-top' : 'rounded-0';
			$this->data['content_color'] = ($content_color) ? $content_color . '-skin' : 'white-skin';
			$this->data['content_shape'] = ($content_shape) ? $content_shape . '-bottom' : 'rounded-0';
		} else {
			$this->data['header_color'] = '';
			$this->data['header_shape'] = '';
			$this->data['content_color'] = '';
			$this->data['content_shape'] = '';
		}

		$this->data['stylesheet_mode'] = $stylesheet_mode;

		// Module
		$this->load->model('design/menu');

		$this->data['menu_vertical'] = array();

		$menu_id = $setting['menu_id'];

		$menu_items = $this->model_design_menu->getMenuItems(0, $menu_id);

		foreach ($menu_items as $menu_item) {
			if (!empty($menu_item['menu_item_link'])) {
				if ($menu_item['external_link']) {
					$href = html_entity_decode($menu_item['menu_item_link'], ENT_QUOTES, 'UTF-8');
				} else {
					$href = $this->url->link($menu_item['menu_item_link'], '', 'SSL');
				}
			} else {
				$href = '';
			}

			$children_data = array();

			$children = $this->model_design_menu->getMenuItems($menu_item['menu_item_id'], $menu_id);

			foreach ($children as $child) {
				if (!empty($child['menu_item_link'])) {
					if ($child['external_link']) {
						$child_href = html_entity_decode($child['menu_item_link'], ENT_QUOTES, 'UTF-8');
					} else {
						$child_href = $this->url->link($child['menu_item_link'], '', 'SSL');
					}
				} else {
					$child_href = '';
				}

				$children_data[] = array(
					'item_id' => $child['menu_item_id'],
					'name'    => $child['name'],
					'href'    => $child_href
				);
			}

			$this->data['menu_vertical'][] = array(
				'item_id'  => $menu_item['menu_item_id'],
				'name'     => $menu_item['menu_item_name'],
				'children' => $children_data,
				'href'     => $href
			);
		}

		// Template
		$this->data['template'] = $this->config->get('config_template');

		$this->data['module'] = $module++;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/' . $this->_name . '.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/' . $this->_name . '.tpl';
		} else {
			$this->template = 'default/template/module/' . $this->_name . '.tpl';
		}

		$this->render();
	}
}
