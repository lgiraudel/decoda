<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Hook;

use Decoda\Hook\AbstractHook;
use \Exception;

/**
 * Converts URLs and emails (not wrapped in tags) into clickable links.
 */
class ClickableHook extends AbstractHook {

	/**
	 * Matches a link or an email, and converts it to an anchor tag.
	 *
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function afterParse($content) {
		try {
			if ($url = $this->getParser()->getFilter('Url')) {
				$chars = preg_quote('-_=;:&?/[]%', '/');
				$protocols = $url->config('protocols');

				$pattern = implode('', array(
					'(' . implode('|', $protocols) . ')s?:\/\/', // protocol
					'([-a-z0-9\.\+]+:[-a-z0-9\.\+]+@)?', // login
					'([-a-z0-9\.]{5,255}+)', // domain, tld
					'(:[0-9]{0,6}+)?', // port
					'([a-z0-9' . $chars . ']+)?', // query
					'(#[a-z0-9' . $chars . ']+)?' // fragment
				));

				$content = preg_replace_callback('/(^|\n|\s)' . $pattern . '/is', array($this, '_urlCallback'), $content);
			}
		} catch (Exception $e) {
		}

		// Based on schema: http://en.wikipedia.org/wiki/Email_address
		try {
			if ($email = $this->getParser()->getFilter('Email')) {
				$pattern = '/(^|\n|\s)([-a-z0-9\.\+!]{1,64}+)@([-a-z0-9]+\.[a-z\.]+)/is';

				$content = preg_replace_callback($pattern, array($this, '_emailCallback'), $content);
			}
		} catch (Exception $e) {
		}

		return $content;
	}

	/**
	 * Callback for email processing.
	 *
	 * @access protected
	 * @param array $matches
	 * @return string
	 */
	protected function _emailCallback($matches) {
		return $matches[1] . $this->getParser()->getFilter('Email')->parse(array(
			'tag' => 'email',
			'attributes' => array()
		), trim($matches[0]));
	}

	/**
	 * Callback for URL processing.
	 *
	 * @access protected
	 * @param array $matches
	 * @return string
	 */
	protected function _urlCallback($matches) {
		return $matches[1] . $this->getParser()->getFilter('Url')->parse(array(
			'tag' => 'url',
			'attributes' => array()
		), trim($matches[0]));
	}

}