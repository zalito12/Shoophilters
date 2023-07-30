/* global SHOOPHILTERS, jQuery */
(function ($) {
	'use strict';

	SHOOPHILTERS.DEBOUNCE_TIME = 650;
	SHOOPHILTERS.MAX_HISTORY = 5;

	class ShoophiltersManage {
		ajaxCount = 0;
		debounce = null;

		historyCache = {};
		historyKeys = [];
		lastUrl = '';

		searchParams = new URLSearchParams();
		groupsParams = {};

		loadingDiv =
			'<div class="shoophilters-loading-container"><div class="shoophilters-loading-image"></div></div>';
		loading = false;
		loadMoreButton =
			'<div class="shoophilters-load-more"><button data-url="%URL%">Load More</button></div>';

		sliderValues = [0, 0];

		constructor(
			paginationType,
			scrollToType,
			resultCountEl,
			contentEl,
			productsEl,
			paginationEl,
			pageEl,
			customScrollEl = null
		) {
			this.paginationType = paginationType;
			this.scrollTo = scrollToType;
			this.resultCountEl = resultCountEl;
			this.contentEl = contentEl;
			this.productsEl = productsEl;
			this.paginationEl = paginationEl;
			this.pageEl = pageEl;
			this.customScrollEl = customScrollEl || contentEl;
		}

		addHistoryCachePage(url, html) {
			if (this.historyKeys.includes(url)) {
				return;
			}

			if (this.historyCache.length === SHOOPHILTERS.MAX_HISTORY) {
				const entry = this.historyKeys.shift();
				delete this.historyCache[entry];
			}

			this.historyCache[url] = html;
			this.historyKeys.push(url);
		}

		getHistoryCachePage(url) {
			return this.historyCache[url];
		}

		setSearchParams(searchParams) {
			this.searchParams = searchParams;
		}

		getParam(filter) {
			if (!this.searchParams) {
				return null;
			}
			return this.searchParams.get(filter);
		}

		setPaginationState(from) {
			if (
				'infinite' === this.paginationType &&
				$(this.paginationEl, from).length
			) {
				const nextEl = $(this.paginationEl + ' a.next', from);
				if (nextEl.length) {
					$(this.paginationEl, from).html(
						this.loadMoreButton.replace(
							'%URL%',
							nextEl.attr('href')
						)
					);
				} else {
					$(this.paginationEl, from).html('');
				}
			}
		}

		initDocument(html) {
			const htmlEl = $(html);
			this.setPaginationState(htmlEl);
			return htmlEl;
		}

		updateTitle(html) {
			const title =
				html
					.substring(
						html.indexOf('<title>'),
						html.lastIndexOf('</title>')
					)
					.replace('<title>', '') || $('title', document).text();
			$('title', document).text($('<div/>').html(title).text());
		}

		initPriceRange(from) {
			const that = this;
			$('.shoophilters-slider-range', from).each(function () {
				const max = $(this).data('max'),
					min = $(this).data('min'),
					step = $(this).data('step');

				const searchParams = new URLSearchParams(
					window.location.search
				);
				const filterMin = searchParams.get('min_price') || min,
					filterMax = searchParams.get('max_price') || max;
				that.sliderValues = [filterMin, filterMax];

				// Init jqueryui slider
				$(this).slider({
					range: true,
					min,
					max,
					step,
					values: [filterMin, filterMax],
					slide(_event, ui) {
						that.updatePriceRangeLabel(
							this,
							ui.values[0],
							ui.values[1]
						);
					},
					change(event, ui) {
						that.onPriceRangeChanges(
							this,
							ui.handleIndex,
							ui.value
						);
					},
				});

				that.updatePriceRangeLabel(this, filterMin, filterMax);
			});
		}

		updatePriceRangeLabel(slider, min, max) {
			const symbol = $(slider).data('symbol');
			const mask = $(slider).data('mask');
			$('.shoophilters-slider-range-label', slider).text(
				`${mask.replace('%1$s', symbol).replace('%2$s', min)} -
					${mask.replace('%1$s', symbol).replace('%2$s', max)}`
			);
		}

		onPriceRangeChanges(slider, handleIndex, value) {
			if (this.sliderValues[handleIndex] === value) {
				return;
			}

			this.sliderValues[handleIndex] = value;
			const navigation = $(slider).data('navigation');
			const filter = $(slider).data('filter-' + handleIndex);
			const group = $(slider).data('group');
			if (
				'ajax' === navigation &&
				filter &&
				value !== this.getParam(filter)
			) {
				this.setFilterOnParams(filter, value);
				this.ajaxLoad(this.getUrlWithParams());
			} else if ('button' === navigation && filter && group) {
				this.setFilterOnGroup(group, filter, value);
			} else if (
				'standard' === navigation &&
				filter &&
				value !== this.getParam(filter)
			) {
				this.setFilterOnParams(filter, value);
				window.location = this.getUrlWithParams();
			}
		}

		replacePage(html) {
			if (this.ajaxCount > 0) {
				return;
			}

			this.updateTitle(html);
			const htmlEl = this.initDocument(html);
			$(this.contentEl).html($(this.contentEl, htmlEl).html());
			this.initPriceRange($(this.contentEl));
			this.scrollAfterLoad();
		}

		scrollAfterLoad() {
			if ('products' === this.scrollTo) {
				$(this.productsEl)[0].scrollIntoView();
			} else if (
				'custom' === this.scrollTo &&
				$(this.customScrollE).length
			) {
				$(this.customScrollE)[0].scrollIntoView();
			} else {
				window.scrollTo(0, 0);
			}
		}

		appendPage(html) {
			const htmlEl = this.initDocument(html);
			this.appendProducts(htmlEl);
			this.replacePagination(htmlEl);
			this.replaceResultCount(htmlEl);
		}

		replacePagination(html) {
			const pagination = $(this.paginationEl, html).html() || '';

			if ($(this.paginationEl).length) {
				$(this.paginationEl).html(pagination);
			}
		}

		replaceResultCount(html) {
			if (
				!$(this.resultCountEl).length ||
				!$('.shoophilters-result-count', html).length
			) {
				return;
			}

			const resultCount = $('.shoophilters-result-count', html)
				.text()
				.replace(
					'-1',
					$('.shoophilters-result-count', html).data('last')
				);

			$(this.resultCountEl).text(resultCount);
		}

		appendProducts(html) {
			const content = $(this.productsEl, html).html() || '';

			if ($(this.productsEl).length) {
				$(this.productsEl).append(content);
			}
		}

		setLoading(state) {
			if (state === this.loading) {
				return;
			}

			this.loading = state;
			if (this.loading) {
				const loadingEl = $(this.loadingDiv);
				loadingEl.appendTo('body');
				$(document).trigger('shoophilters:showLoading', loadingEl);
			} else {
				$('.shoophilters-loading-container').remove();
			}
		}

		fireAjaxCall(
			now,
			data,
			successCallback,
			errorCallback,
			completeCallback
		) {
			if (data) {
				this.setLoading(true);

				const call = () =>
					$.ajax(data)
						.done((response) => {
							this.ajaxCount--;
							if (successCallback) {
								successCallback(response);
							}
						})
						.fail((response) => {
							this.ajaxCount--;
							if (errorCallback) {
								errorCallback(response);
							}
						})
						.always((response) => {
							if (completeCallback) {
								completeCallback(response);
							}
							if (0 === this.ajaxCount) {
								this.setLoading(false);
							}
						});

				if (now) {
					this.ajaxCount++;
					call();
				} else {
					if (this.debounce) {
						clearTimeout(this.debounce);
					}
					this.debounce = setTimeout(() => {
						this.ajaxCount++;
						call();
					}, SHOOPHILTERS.DEBOUNCE_TIME);
				}
			}
		}

		ajaxLoad(url, now = false, skipState = false) {
			if (url === window.location.href && !skipState) {
				return;
			}

			this.fireAjaxCall(
				now,
				{
					method: 'GET',
					url,
					dataType: 'html',
				},
				(response) => {
					if (!skipState) {
						window.history.pushState({ url }, null, url);
						this.lastUrl =
							window.location.pathname + window.location.search;
					}
					this.addHistoryCachePage(url, response);
					this.updateSearchParams(url);
					this.replacePage(response);
				},
				(jqXHR, textStatus, errorThrown) => {
					this.ajaxError(jqXHR, textStatus, errorThrown);
				}
			);
		}

		loadInifiniteScrollPage(url) {
			this.fireAjaxCall(
				true,
				{
					method: 'GET',
					url,
					dataType: 'html',
				},
				(response) => {
					this.appendPage(response);
				},
				(jqXHR, textStatus, errorThrown) => {
					this.ajaxError(jqXHR, textStatus, errorThrown);
				}
			);
		}

		ajaxError(jqXHR, textStatus, errorThrown) {
			console.log(jqXHR); // eslint-disable-line
			console.log(textStatus); // eslint-disable-line
			console.log(errorThrown); // eslint-disable-line
		}

		getUrlWithParams(resetPagination = true) {
			return ShoophiltersManage.getUrlAppendParams(
				window.location.origin + window.location.pathname,
				this.searchParams,
				resetPagination
			);
		}

		getUrlWithGroupParams(group, resetPagination = true) {
			return ShoophiltersManage.getUrlAppendParams(
				window.location.origin + window.location.pathname,
				this.getGroupParams(group),
				resetPagination
			);
		}

		static getUrlAppendParams(url, params, resetPagination = true) {
			url = resetPagination
				? ShoophiltersManage.getUrlWithoutPage(url)
				: url;
			const newParams = new URLSearchParams(params);
			if (newParams.has('page')) {
				newParams.delete('page');
			}
			return '' !== newParams.toString()
				? url + '?' + newParams.toString()
				: url;
		}

		static getUrlWithoutPage(url) {
			const pathParts = (url || '').split('/');
			const pageIndex = pathParts.indexOf('page');
			if (pageIndex !== -1) {
				url = pathParts.slice(0, pageIndex).join('/');
			}

			return url;
		}

		static getAttributeFilterSearchParams(item, currentParams) {
			const li = $(item);
			const searchParams = new URLSearchParams(currentParams);

			const value = li.data('value');
			const filter = li.data('filter');
			const query = li.data('query');

			const checkbox = $('input[type=checkbox]', li);
			const mustFilter = !checkbox.prop('checked');
			checkbox.prop('checked', mustFilter);

			if (searchParams.has(filter)) {
				const values = searchParams
					.get(filter)
					.split(',')
					.filter((v) => v.toString() !== value.toString());

				if (mustFilter) {
					values.push(value);
				}

				if (values.length > 0) {
					searchParams.set(filter, values.sort().join(','));
				} else {
					searchParams.delete(filter);
					searchParams.delete(query);
				}
			} else if (mustFilter) {
				searchParams.append(filter, value);
				searchParams.append(query, 'or');
			}

			return searchParams;
		}

		addAttributeFilterToParams(item) {
			this.searchParams =
				ShoophiltersManage.getAttributeFilterSearchParams(
					item,
					this.searchParams
				);
		}

		addAttributeFilterToGroup(group, item) {
			this.setGroupParams(
				group,
				ShoophiltersManage.getAttributeFilterSearchParams(
					item,
					this.getGroupParams(group)
				)
			);
		}

		addCategoryFilterToGroup(group, item) {
			const li = $(item);
			const searchParams = new URLSearchParams(
				this.getGroupParams(group)
			);

			const value = li.data('value');
			const filter = li.data('filter');

			const radio = $('input[type=radio]', li);
			radio.prop('checked', true);

			searchParams.set(filter, value);
			this.setGroupParams(group, searchParams);
		}

		setFilterOnParams(filter, value) {
			if (!this.searchParams) {
				this.searchParams = new URLSearchParams();
			}
			this.searchParams.set(filter, value);
		}

		setFilterOnGroup(group, filter, value) {
			const params = this.getGroupParams(group);
			params.set(filter, value);
			this.setGroupParams(group, params);
		}

		getGroupParams(group) {
			return (
				this.groupsParams[group] ||
				new URLSearchParams(this.searchParams)
			);
		}

		setGroupParams(group, searchParams) {
			this.groupsParams[group] = searchParams;
		}

		updateSearchParams(url) {
			let params = new URLSearchParams();
			const parts = (url || '').split('?');
			if (parts.length > 1) {
				params = new URLSearchParams(parts[1]);
			}

			this.setSearchParams(params);
			for (const key of Object.keys(this.groupsParams)) {
				this.groupsParams[key] = params;
			}
		}

		resetGroupFiltersAndReload(group) {
			const currentParams = new URLSearchParams(window.location.search);

			$('body')
				.find('.shoophilters-filter-group-' + group)
				.each(function () {
					const filter = $(this).data('filter');
					if (filter) {
						currentParams.delete(filter);
					}

					const query = $(this).data('query');
					if (query) {
						currentParams.delete(query);
					}
				});

			const url = ShoophiltersManage.getUrlAppendParams(
				window.location.origin + window.location.pathname,
				currentParams,
				true
			);

			this.ajaxLoad(url, true);
			this.setGroupParams(group, new URLSearchParams());
		}
	}

	$(function () {
		const shoophiltersManage = new ShoophiltersManage(
			SHOOPHILTERS.pagination.paginationType,
			SHOOPHILTERS.pagination.scrollTo,
			SHOOPHILTERS.selectors.resultCount,
			SHOOPHILTERS.selectors.content,
			SHOOPHILTERS.selectors.products,
			SHOOPHILTERS.selectors.pagination,
			SHOOPHILTERS.selectors.page,
			SHOOPHILTERS.pagination.scrollToCustom
		);

		shoophiltersManage.lastUrl =
			window.location.pathname + window.location.search;
		shoophiltersManage.setSearchParams(
			new URLSearchParams(window.location.search)
		);
		shoophiltersManage.setPaginationState();
		shoophiltersManage.initPriceRange();

		$(window).on('popstate', function () {
			// if the state is the page you expect, pull the name and load it.
			if (history.state && history.state.url) {
				shoophiltersManage.lastUrl =
					location.pathname + location.search;
				const html = shoophiltersManage.getHistoryCachePage(
					history.state.url
				);
				if (html) {
					shoophiltersManage.replacePage(html);
				} else {
					shoophiltersManage.ajaxLoad(history.state.url, true, true);
				}
			} else if (
				shoophiltersManage.lastUrl !==
				location.pathname + location.search
			) {
				location.reload();
			}
		});

		/* Ajax Infinite scroll button */
		$('body').on('click', '.shoophilters-load-more button', (e) => {
			const buttonEl = $(e.currentTarget);
			shoophiltersManage.loadInifiniteScrollPage(buttonEl.data('url'));
		});

		/* Ajax change page number */
		$('body').on('click', shoophiltersManage.pageEl, (e) => {
			e.preventDefault();
			const url = $(e.currentTarget).prop('href');
			shoophiltersManage.ajaxLoad(url, true);
		});

		/* Ajax Category filtering without button */
		$('body').on(
			'click',
			'.shoophilters-category-item.shoophilters-filter-navigation-ajax a',
			(e) => {
				e.preventDefault();
				const url = $(e.currentTarget).prop('href');

				shoophiltersManage.ajaxLoad(url, true);
			}
		);

		/* Ajax button category prevent link navigation */
		$('body').on(
			'click',
			'.shoophilters-category-item.shoophilters-filter-navigation-button a',
			(e) => {
				e.preventDefault();
			}
		);

		/* Ajax button category navigation */
		$('body').on(
			'click',
			'.shoophilters-category-item.shoophilters-filter-navigation-button',
			(e) => {
				//e.preventDefault();
				const group = $(e.currentTarget).data('group');
				shoophiltersManage.addCategoryFilterToGroup(
					group,
					e.currentTarget
				);
			}
		);

		/* Attribute filtering without ajax (page reload) */
		$('body').on(
			'click',
			'.shoophilters-attribute-item.shoophilters-filter-navigation-standard',
			(e) => {
				e.preventDefault();

				shoophiltersManage.addAttributeFilterToParams(e.currentTarget);
				window.location = shoophiltersManage.getUrlWithParams();

				return false;
			}
		);

		/* Ajax Attribute filtering without button */
		$('body').on(
			'click',
			'.shoophilters-attribute-item.shoophilters-filter-navigation-ajax',
			(e) => {
				e.preventDefault();

				shoophiltersManage.addAttributeFilterToParams(e.currentTarget);
				shoophiltersManage.ajaxLoad(
					shoophiltersManage.getUrlWithParams()
				);

				return false;
			}
		);

		/* Attribute filtering with button */
		$('body').on(
			'click',
			'.shoophilters-attribute-item.shoophilters-filter-navigation-button',
			(e) => {
				e.preventDefault();
				const group = $(e.currentTarget).data('group');

				shoophiltersManage.addAttributeFilterToGroup(
					group,
					e.currentTarget
				);

				return false;
			}
		);

		/* Apply filters button */
		$('body').on('click', '.shoophilters-filter-button', (e) => {
			e.preventDefault();
			const group = $(e.currentTarget).data('group');
			if (!group) {
				return;
			}

			shoophiltersManage.ajaxLoad(
				shoophiltersManage.getUrlWithGroupParams(group)
			);

			return false;
		});

		/* Remove filters button */
		$('body').on('click', '.shoophilters-filter-remove-button', (e) => {
			e.preventDefault();
			const group = $(e.currentTarget).data('group');
			if (!group) {
				return;
			}

			shoophiltersManage.resetGroupFiltersAndReload(group);

			return false;
		});

		/* Order dropdown standard */
		$('body').on(
			'change',
			'select.shoophilters-orderby-select.shoophilters-filter-navigation-standard',
			(e) => {
				$(e.currentTarget).closest('form').trigger('submit');
			}
		);

		/* Order dropdown ajax */
		$('body').on(
			'change',
			'select.shoophilters-orderby-select.shoophilters-filter-navigation-ajax',
			(e) => {
				const selectEl = $(e.currentTarget);

				const filter = selectEl.attr('name');
				const value = selectEl.val();

				if (filter) {
					shoophiltersManage.setFilterOnParams(filter, value);
					shoophiltersManage.ajaxLoad(
						shoophiltersManage.getUrlWithParams()
					);
				}
			}
		);

		/* Order dropdown apply button */
		$('body').on(
			'change',
			'select.shoophilters-orderby-select.shoophilters-filter-navigation-button',
			(e) => {
				const selectEl = $(e.currentTarget);

				const group = selectEl.data('group');
				const filter = selectEl.attr('name');
				const value = selectEl.val();

				if (filter && group) {
					shoophiltersManage.setFilterOnGroup(group, filter, value);
				}
			}
		);

		/* Order list standard */
		$('body').on(
			'click',
			'li.shoophilters-orderby-item.shoophilters-filter-navigation-standard:not(.current) .shoophilters-orderby-name',
			(e) => {
				const liEl = $(e.currentTarget).closest(
					'li.shoophilters-orderby-item'
				);

				const filter = liEl.data('filter');
				const value = liEl.data('value');

				if (filter && value !== shoophiltersManage.getParam(filter)) {
					shoophiltersManage.setFilterOnParams(filter, value);
					window.location = shoophiltersManage.getUrlWithParams();
				}
			}
		);

		/* Order list ajax */
		$('body').on(
			'click',
			'li.shoophilters-orderby-item.shoophilters-filter-navigation-ajax:not(.current) .shoophilters-orderby-name',
			(e) => {
				const liEl = $(e.currentTarget).closest(
					'li.shoophilters-orderby-item'
				);

				const filter = liEl.data('filter');
				const value = liEl.data('value');

				if (filter && value !== shoophiltersManage.getParam(filter)) {
					shoophiltersManage.setFilterOnParams(filter, value);
					shoophiltersManage.ajaxLoad(
						shoophiltersManage.getUrlWithParams()
					);
				}
			}
		);

		/* Order list button group */
		$('body').on(
			'click',
			'li.shoophilters-orderby-item.shoophilters-filter-navigation-button .shoophilters-item-radio-label',
			(e) => {
				const liEl = $(e.currentTarget).closest(
					'li.shoophilters-orderby-item'
				);

				const radio = $('input[type=radio]', liEl);
				radio.prop('checked', true);

				const group = liEl.data('group');
				const filter = liEl.data('filter');
				const value = liEl.data('value');

				if (filter && group) {
					shoophiltersManage.setFilterOnGroup(group, filter, value);
				}
			}
		);
	});
})(jQuery);
