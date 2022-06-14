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
    console.log('i am category')
    checkFilteredCategory(prestashop);
  }
  
});

function checkFilteredCategory(data) {

  let newData = [],
      filteredCategory = [
        ['category-id-170','.manufacturer'],
        ['category-id-166','.manufacturer'],
        ['category-id-36','.manufacturer'],
        ['category-id-46','.manufacturer'],
        ['category-id-54','.manufacturer'],
        ['category-id-157','.manufacturer'],
        ['category-id-79','.manufacturer'],
        ['category-id-89','.manufacturer'],
        ['category-id-107','.manufacturer'],
        ['category-id-115','.manufacturer']
      ];

  newData = Object.entries(data.page.body_classes);

  newData.forEach(function(newdata) {
    if( filteredCategory[0][0].includes(newdata[0]) ) {
      activateFilter(filteredCategory[0][1]);
    }
  });
}


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

function activateFilter(facetType) {
 
  $('.filter-top-show').addClass('open');
  $('.js-filter-wrapper').addClass('filter-wrapper--show');
  $('.js-listing-wrapper').removeClass('listing-wrapper--default');

  $('a'+facetType).attr('aria-expanded', true);
  $('div'+facetType).addClass('show');
}