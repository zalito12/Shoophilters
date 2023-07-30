/* global jQuery */
(function ($) {
	'use strict';

	window.dropDownListFilter = class {
		hostId = null;
		container = null;
		placeholder = null;
		valueContainer = null;
		dataList = null;
		callbackOnSelect = null;
		callbackFilter = null;
		open = false;
		constructor(
			hostId,
			initialValue,
			placeholderText,
			placeholderInputText,
			initialDataList = [],
			callbackOnSelect = null,
			callbackFilter = null
		) {
			this.hostId = '#' + hostId;
			this.generateComponent(
				initialValue,
				placeholderText,
				placeholderInputText
			);
			this.populateDataList(initialDataList);
			this.addDataToItemsList();
			this.callbackOnSelect = callbackOnSelect || (() => false);
			this.callbackFilter = callbackFilter || this.filterList;

			this.container.on(
				'click',
				'.woofilters-input-dropdown-selection',
				() => {
					if (this.open) {
						this.container.removeClass(
							'woofilters-dropdown-active'
						);
						this.open = false;
					} else {
						this.container.addClass('woofilters-dropdown-active');
						this.open = true;
					}
				}
			);

			this.container.on(
				'click',
				'.woofilters-input-dropdown-list-filter .woofilters-input-dropdown-data-item',
				(e) => {
					this.container.removeClass('woofilters-dropdown-active');
					this.container.removeClass('woofilters-dropdown-no-value');
					this.open = false;

					const inputValue = this.container.find(
						'.woofilters-input-dropdown-value'
					);
					inputValue.text($(e.target).text());
					this.callbackOnSelect($(e.target));
				}
			);

			this.container.on(
				'input',
				'.woofilters-input-dropdown-list-filter .woofilters-input-dropdown-filter-input',
				$.debounce(250, (e) => {
					const value = $(e.target).val();
					this.callbackFilter(value, this.onFilterCallback, this);
				})
			);

			this.container.on(
				'click',
				'.woofilters-input-dropdown-selection .woofilters-input-dropdown-clear',
				() => {
					this.container.removeClass('woofilters-dropdown-active');
					this.container.addClass('woofilters-dropdown-no-value');
					this.open = false;

					this.container
						.find('.woofilters-input-dropdown-value')
						.text('');
					this.callbackOnSelect(null);
				}
			);

			$(document).on('click', (e) => {
				const target = $(e.target);
				if (!this.container.has(target).length) {
					this.container.removeClass('woofilters-dropdown-active');
					this.open = false;
				}
			});
		}

		filterList(query) {
			this.container
				.find('ul.woofilters-input-dropdown-data')
				.children()
				.each(function () {
					if (
						$(this)
							.text()
							.toLowerCase()
							.includes(query.toLowerCase())
					) {
						$(this).removeClass('hidden');
					} else {
						$(this).addClass('hidden');
					}
				});
		}
		onFilterCallback(self, newData) {
			if (newData) {
				self.populateDataList(newData);
				self.addDataToItemsList();
			}
		}
		generateComponent(initialValue, placeholderText, placeholderInputText) {
			const placeholder = placeholderInputText
				? placeholderInputText
				: 'Start typing...';
			$(this.hostId).html(
				'<div class="woofilters-input-dropdown-container regular-text" tabindex="1"><span class="woofilters-input-dropdown-selection"><span class="woofilters-input-dropdown-placeholder"></span><span class="woofilters-input-dropdown-value"></span><span class="woofilters-input-dropdown-clear">x</span><span class="woofilters-input-dropdown-arrow"></span></span><div class="woofilters-input-dropdown-list-filter"><input type="text" class="woofilters-input-dropdown-filter-input" placeholder="' +
					placeholder +
					'" autocomplete="off" /><ul class="woofilters-input-dropdown-data"></ul></div></div>'
			);
			this.container = $(this.hostId).children(
				'.woofilters-input-dropdown-container'
			);
			this.placeholder = this.container.find(
				'.woofilters-input-dropdown-placeholder'
			);
			this.valueContainer = this.container.find(
				'.woofilters-input-dropdown-value'
			);
			if (!initialValue) {
				this.container.addClass('woofilters-dropdown-no-value');
			} else {
				this.valueContainer.html(initialValue);
			}
			this.placeholder.text(
				placeholderText ? placeholderText : 'Select...'
			);
		}
		addDataToItemsList() {
			const list = this.container.find(
				'ul.woofilters-input-dropdown-data'
			);
			list.empty();
			this.dataList.children().each(function () {
				list.append(
					'<li class="woofilters-input-dropdown-data-item" data-id="' +
						$(this).data('id') +
						'">' +
						$(this).data('value') +
						'</li>'
				);
			});
		}
		populateDataList(data) {
			if (!this.dataList) {
				this.dataList = $('<ul style="display:none"></ul>');
				this.container.append(this.dataList);
			}
			this.dataList.empty();
			if (data && data.length) {
				data.forEach((item) => {
					this.dataList.append(
						'<li data-id="' +
							item.id +
							'" data-value="' +
							item.value +
							'"></li>'
					);
				});
			}
		}
	};
})(jQuery);
