import $ from 'jquery';
import prestashop from 'prestashop';
import Filters from './components/filters/Filters';

$(() => {
  /* eslint no-unused-vars: ["error", { "varsIgnorePattern": "filters" }] */
  const filters = new Filters();

  // console.log(filters);

  prestashop.on('updateProductList', (data) => {
    updateProductListDOM(data);
    window.scrollTo(0, 0);
  });

  prestashop.on('updatedProductList', () => {
    prestashop.pageLazyLoad.update();
  });

  if(prestashop.page.body_classes['page-category']) {
    checkFilteredCategory(prestashop);
  }
  
});

function updateProductListDOM(data) {
  $('#search_filters').replaceWith(data.rendered_facets);
  $('#js-active-search-filters').replaceWith(data.rendered_active_filters);
  $('#js-product-list-top').replaceWith(data.rendered_products_top);
  $('#js-product-list').replaceWith(data.rendered_products);
  $('#js-product-list-bottom').replaceWith(data.rendered_products_bottom);
  if (data.rendered_products_header) {
    $('#js-product-list-header').replaceWith(data.rendered_products_header);
  }

  prestashop.customSelect.init();
  prestashop.emit('updatedProductList', data);

}

function checkFilteredCategory(data) {

  let newData = [],
      filteredCategory = {
        "category-id-166" : '.manufacturer', // open brand inspiration
        "category-id-36"  : '.manufacturer', // ""
        "category-id-46"  : '.manufacturer', // ""
        "category-id-54"  : '.manufacturer', // ""
        "category-id-157" : '.manufacturer', // ""
        "category-id-79"  : '.manufacturer', // ""
        "category-id-89"  : '.manufacturer', // ""
        "category-id-107" : '.manufacturer', // ""
        "category-id-115" : '.manufacturer', // ""
        "category-id-170" : '.feature_1', // open fragrance group
        "category-id-83"  : '.feature_1', // ""
        "category-id-169" : '.feature_2', // open time of year
        "category-id-82"  : '.feature_2', // ""
        "category-id-168" : '.feature_4', // open collection
        "category-id-81"  : '.feature_4', // ""
        // "category-id-164" : '.feature_7', // open bestsellers
        // "category-id-34"  : '.feature_7', // ""
        // "category-id-44"  : '.feature_7', // ""
        // "category-id-52"  : '.feature_7', // ""
        // "category-id-155" : '.feature_7', // ""
        // "category-id-77"  : '.feature_7', // ""
        // "category-id-87"  : '.feature_7', // ""
        // "category-id-105" : '.feature_7', // ""
        // "category-id-113" : '.feature_7', // ""
        // "category-id-173" : '.feature_5', // open unisex
        // "category-id-41"  : '.feature_5', // ""
        // "category-id-51"  : '.feature_5', // ""
        // "category-id-59"  : '.feature_5', // ""
        // "category-id-162" : '.feature_5', // ""
        // "category-id-86"  : '.feature_5', // ""
        // "category-id-94"  : '.feature_5', // ""
        // "category-id-112" : '.feature_5', // ""
        // "category-id-121" : '.feature_5', // ""
      };

  newData = Object.entries(data.page.body_classes);

  newData.forEach( (newdata) => {
    if( Object.keys(filteredCategory).includes(newdata[0]) ) {
      Object.entries(filteredCategory).forEach( (filter_class) => {
        if(filter_class[0] === newdata[0]) {
          activateFilter( filter_class[1] );
        }
      }); 
    }
  });

}

function activateFilter(facetType) {
  console.log(facetType)
  $('.filter-top-show').addClass('open');
  $('.js-filter-wrapper').addClass('filter-wrapper--show');
  $('.js-listing-wrapper').removeClass('listing-wrapper--default');

  // open mobile filter
  $('.js-filtermobile-slider').addClass('istoggled');
  $('.js-search-filters').css('display','block');

  $('a'+facetType).attr('aria-expanded', true);
  $('div'+facetType).addClass('show');
}