"use strict";

// ----- FUNCTIONS
function scrollAnimateToBlock(event,block,offset){//block
  event.preventDefault();
  let dest = 0;
  let top = offset || 0;
  if (block){
    dest = $(block).offset().top + top;
  }
  $('html, body').animate({scrollTop: dest}, 700);
}

function missClick(selector,event){
  let div = $(selector);
  if (!div.is(event.target) && div.has(event.target).length === 0) {
    return true
  }else {
    return false;
  }
}

function initMask(){
  // (data-inputmask="'mask': '+7(999)999-99-99','clearIncomplete':'true'")
  $('[data-inputmask]').inputmask({
    showMaskOnHover: false
  });
  $('.js-mask-tel').inputmask({
    showMaskOnHover: false,
    mask: "+7(XXX)-XXX-XX-XX",
    clearIncomplete: true,
    definitions: {
        "X": {
            validator: "[0-9]",
        }
    }
  });
}

$.fancybox.defaults.autoFocus = false;
$.fancybox.defaults.i18n.en = {
    CLOSE: "",
    NEXT: "",
    PREV: "",
    ERROR: "Запрошенный контент не может быть загружен. <br/> Повторите попытку позже.",
    PLAY_START: "",
    PLAY_STOP: "",
    FULL_SCREEN: "",
    THUMBS: "",
    DOWNLOAD: "",
    SHARE: "",
    ZOOM: ""
  };
// ------ MAIN
$(document).ready(function(){
  let deviceUser = device.default;

  //bsCustomFileInput.init();

  // добавление класса при отправке товара в корзину для смены кнопок
  $('.js-add-basket-wrap .js-add-basket').on('click', function() {
      $(this).closest('.js-add-basket-wrap').addClass('in_basket');
  });

  $('.js-add-basket').click(function() {
    ym(88715282,'reachGoal','addToCart')
      dataLayer.push({'event': 'add_to_cart'});
  });
  $('#soa-form').on('submit', () => {
    ym(88715282,'reachGoal','orderCreate');
  })

  $('.link--favorite').click(function() {
      if(!$(this).hasClass('is-selected')) {
          dataLayer.push({'event': 'add_to_favorite'});
      }
  });

  $('.link--chart').click(function() {
      if(!$(this).hasClass('is-selected')) {
          dataLayer.push({'event': 'add_to_comparison'});
      }
  });

  $('.rbs__payment-link').click(function() {
    dataLayer.push({'event': 'payment'});
  });

  $('[data-toggle="popover"]').each(function(i,el){
    $(el).popover({
      container: $(el).parent(),
      html: true
    })
  });
  
  initMask();
  formValidation();
  initSelect2();
  
  Scrollbar.initAll({
    continuousScrolling: true
  });
});

$(document).on('mouseenter touchstart', '#catalogMenu', function() {
  let menuContainer = $('#catalog');
  
  if (menuContainer.hasClass('__loading')) {
    $.get('/local/ajax/popups/catalog.php', function(res) {
      menuContainer.removeClass('__loading');
      menuContainer.html(res);
      let catalogScrollEl = menuContainer.find('.catalog__scroll').get(0);
      if (catalogScrollEl) {
        Scrollbar.init(catalogScrollEl, {});
      }
    });
  }
})

$(document).on('click', '.js-show-all', function(e) {
  let content = $(this).closest('.seo-block__content').find('.seo-block-description');
  e.preventDefault();
  content.toggleClass('collapsed');

  if (content.hasClass('collapsed')) {
    $(this).text('Развернуть')
  } else {
    $(this).text('Свернуть')
  }
});

$(document).on('click', '.link[data-fancybox]', function() {
  let subsectionContainer = $( $(this).attr('href') );
  let catalogScrollRes = subsectionContainer.find('.catalog__scroll');
  if (catalogScrollRes.length && !catalogScrollRes.find('.scroll-content').length) {
    Scrollbar.init(catalogScrollRes.get(0), {});
  }
})

$(document).on('click','[data-fancybox][data-close-existing]',function(e){
  //$('.fancybox-is-open [data-fancybox-close]').click();
});


$(document).on('afterShow.fb', function( e, instance, slide ) {
  if (instance.$trigger == undefined) {
    return;
  }
  let trigger = $(instance.$trigger[0])
  let tab = trigger.data('fancybox-tab');
  if (tab){
    $('[href="'+tab+'"]').click();
  }
});

function cardShowType(link,e){
  e.preventDefault();
  let btn = $(link);
  let btnActive = btn.parent().find('.is-active');
  let cards = $('.product-row .card'); 
  
  if(!btn.hasClass('is-active')){
    btnActive.removeClass('is-active');
    btn.addClass('is-active');
    cards.toggleClass('card--row');
  }
}

document.addEventListener("DOMContentLoaded", function(){

  let arrows = ['<svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">' +
  '<rect x="0.497437" y="15.9519" width="6.71067" height="21.4741" transform="rotate(-45 0.497437 15.9519)" fill="white"/>' +
  '<rect x="5.24243" y="20.6971" width="6.71067" height="21.4741" transform="rotate(-135 5.24243 20.6971)" fill="white"/>' +
  '<path d="M8.08953 23.5442L5.24243 20.6971L9.98759 15.952L12.8347 18.7991L8.08953 23.5442Z" fill="url(#paint0_linear)"/>' +
  '<defs>' +
  '<linearGradient id="paint0_linear" x1="7.6135" y1="18.3245" x2="10.4613" y2="21.1724" gradientUnits="userSpaceOnUse">' +
  '<stop stop-color="#CCCCCC"/>' +
  '<stop offset="1" stop-color="white" stop-opacity="0"/>' +
  '</linearGradient>' +
  '</defs>' +
  '</svg>','<svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">' +
  '<rect width="6.71067" height="21.4741" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 30.8661 15.9519)" fill="white"/>' +
  '<rect width="6.71067" height="21.4741" transform="matrix(0.707107 -0.707107 -0.707107 -0.707107 26.1211 20.6971)" fill="white"/>' +
  '<path d="M23.274 23.5442L26.1211 20.6971L21.3759 15.952L18.5288 18.7991L23.274 23.5442Z" fill="url(#paint123_linear)"/>' +
  '<defs>' +
  '<linearGradient id="paint123_linear" x1="23.75" y1="18.3245" x2="20.9022" y2="21.1724" gradientUnits="userSpaceOnUse">' +
  '<stop stop-color="#CCCCCC"/>' +
  '<stop offset="1" stop-color="white" stop-opacity="0"/>' +
  '</linearGradient>' +
  '</defs>' +
  '</svg>'];

  Array.from(document.querySelectorAll('.js-carousel-front-right')).forEach(function(el,i){
    let carousel = tns({
      container: el,
      items: 1,
      speed: 400,
      autoplay: true,
      autoplayButtonOutput: false,
      gutter: 24,
      nav: true,
      navPosition: 'bottom',
      controls: false,
      controlsPosition: 'bottom',
      controlsText: arrows,
      mouseDrag: true,
      preventScrollOnTouch: 'auto',
      swipeAngle: 25,
      touch: true,
      loop: false,
      rewind: true,
      onInit: function(slider){

      }
    });
  });

  /*Array.from(document.querySelectorAll('.js-carousel-logo')).forEach(function(el,i){
    let carousel = tns({
      container: el,
      items: 1,
      speed: 500,
      gutter: 0,
      nav: false,
      controls: true,
      controlsPosition: 'bottom',
      controlsText: arrows,
      mouseDrag: true,
      preventScrollOnTouch: 'auto',
      swipeAngle: 25,
      touch: true,
      loop: true,
      rewind: false,
      autoplay: true,
      autoplayButtonOutput: false,
      responsive: {
        460: {
          items: 2
        },
        640: {
          items: 3
        },
        992: {
          items: 4
        },
        1200: {
          items: 5
        },
        1560: {
          items: 6
        }
      },
      onInit: function(slider){
        
      }
    });
  }); Если нужно будет раскомментировать */
  
  function cardRadiusCarousel(carousel){
    let active = $(carousel).find('.tns-slide-active');
    active.removeClass('is-first is-last');
    active.first().addClass('is-first');
    active.last().addClass('is-last');
  }
  
  var catalogCards = document.querySelectorAll('.js-carousel-cards');
  
  if (catalogCards.length > 0) {
    Array.from(document.querySelectorAll('.js-carousel-cards')).forEach(function(el,i){
    let carousel = tns({
        container: el,
        items: 2,
        speed: 500,
        gutter: 0,
        nav: false,
        controls: true,
        controlsPosition: 'bottom',
        controlsText: arrows,
        mouseDrag: true,
        preventScrollOnTouch: 'auto',
        swipeAngle: 25,
        touch: true,
        loop: true,
        rewind: false,
        responsive: {
          640: {
            items: 4
          },
          1560: {
            items: 5
          }
        },
        onInit: function(slider){
          cardRadiusCarousel(el);
        }
      });
      carousel.events.on('indexChanged',function(){
        cardRadiusCarousel(el);
      });
      carousel.events.on('newBreakpointEnd',function(){
        cardRadiusCarousel(el);
      });
    });
  }

  Array.from(document.querySelectorAll('.js-carousel-news')).forEach(function(el,i){
    let carousel = tns({
      container: el,
      items: 1,
      speed: 500,
      gutter: 16,
      nav: true,
      navPosition: 'bottom',
      controls: false,
      controlsPosition: 'bottom',
      controlsText: arrows,
      mouseDrag: true,
      preventScrollOnTouch: 'auto',
      swipeAngle: 25,
      touch: true,
      loop: true,
      rewind: false,
      
      responsive: {
        640: {
          items: 2,
          gutter: 24
        },
        992: {
          controls: true
        },
        1200: {
          items: 3,
          gutter: 16,
        },
        1560: {
          gutter: 32
        }
      },
      onInit: function(slider){

      }
    });
  });

  var carouselCatalog = document.querySelectorAll('.js-carousel-catalog');

  console.log(carouselCatalog);

  Array.from(document.querySelectorAll('.js-carousel-catalog')).forEach(function(el,i){
    let carousel = tns({
      container: el,
      items: 1,
      speed: 500,
      gutter: 16,
      nav: false,
      controls: true,
      controlsPosition: 'bottom',
      controlsText: arrows,
      mouseDrag: true,
      preventScrollOnTouch: 'auto',
      swipeAngle: 25,
      touch: true,
      loop: false,
      rewind: true,

      responsive: {
        640: {
          items: 2,
          gutter: 24
        },
        1200: {
          items: 3,
          gutter: 16,
        },
        1560: {
          gutter: 32
        }
      },
      onInit: function(slider){

      }
    });
  });

  Array.from(document.querySelectorAll('.js-carousel-abc')).forEach(function(el,i){
    
    let carousel = tns({
      container: el,
      items: 1,
      speed: 500,
      gutter: 0,
      nav: false,
      controls: true,
      controlsPosition: 'bottom',
      controlsText: arrows,
      mouseDrag: true,
      preventScrollOnTouch: 'auto',
      swipeAngle: 25,
      touch: true,
      loop: false,
      rewind: true,
      responsive: {
        460: {
          items: 2
        },
        640: {
          items: 3
        },
        992: {
          items: 4
        },
        1200: {
          items: 5
        }
      },
      onInit: function(slider){
        $('#nav-brands [data-slide]').click(function(){
          $('#nav-brands [data-slide]').removeClass('is-active');
          console.log($(this))
          carousel.goTo($(this).data('slide'));
          $(this).addClass('is-active');
        });
      }
    });

    carousel.events.on('indexChanged', function(info,event){
      $('#nav-brands [data-slide]').removeClass('is-active');
      $(`#nav-brands [data-slide="${info.index}"]`).addClass('is-active');
      $('#carousel-abc .tns-item').removeClass('is-active');
      $(`#carousel-abc-item${info.index}`).addClass('is-active');
    });
    
  });

  Array.from(document.querySelectorAll('.js-carousel-analog')).forEach(function(el,i){
    let carousel = tns({
      container: el,
      items: 1,
      fixedWidth: 208,
      speed: 500,
      gutter: 0,
      nav: false,
      controls: true,
      controlsPosition: 'bottom',
      controlsText: arrows,
      mouseDrag: true,
      preventScrollOnTouch: 'auto',
      swipeAngle: 25,
      touch: true,
      loop: false,
      rewind: false
    });
  });

  Array.from(document.querySelectorAll('#carousel-product-block')).forEach(function(el,i){
    let thumbs;
    let carousel = tns({
      container: el,
      mode: 'gallery',
      items: 1,
      speed: 500,
      gutter: 0,
      nav: true,
      navContainer: '#carousel-product-block-nav',
      controls: false,
      controlsPosition: 'bottom',
      mouseDrag: true,
      preventScrollOnTouch: 'auto',
      swipeAngle: 25,
      touch: true,
      loop: false,
      rewind: true,
      lazyload: true,
      lazyloadSelector: '.tns-lazy',
      onInit: function(slider){
        thumbs = tns({
          container: '#carousel-product-block-nav',
          items: 5,
          speed: 500,
          gutter: 0,
          nav: false,
          controls: true,
          controlsPosition: 'bottom',
          controlsText: ['<svg width="7" height="12" viewBox="0 0 7 12" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.70711 1.70711C7.09763 1.31658 7.09763 0.683417 6.70711 0.292893C6.31658 -0.0976308 5.68342 -0.0976307 5.29289 0.292893L0.292893 5.29289C-0.0976313 5.68342 -0.0976313 6.31658 0.292893 6.70711L5.29289 11.7071C5.68342 12.0976 6.31658 12.0976 6.70711 11.7071C7.09763 11.3166 7.09763 10.6834 6.70711 10.2929L2.41421 6L6.70711 1.70711Z" fill="currentColor"/></svg>','<svg width="7" height="12" viewBox="0 0 7 12" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M0.292894 1.70711C-0.0976305 1.31658 -0.0976305 0.683417 0.292894 0.292893C0.683418 -0.0976308 1.31658 -0.0976307 1.70711 0.292893L6.70711 5.29289C7.09763 5.68342 7.09763 6.31658 6.70711 6.70711L1.70711 11.7071C1.31658 12.0976 0.683418 12.0976 0.292893 11.7071C-0.0976309 11.3166 -0.0976309 10.6834 0.292893 10.2929L4.58579 6L0.292894 1.70711Z" fill="currentColor"/></svg>'],
          mouseDrag: true,
          preventScrollOnTouch: 'auto',
          swipeAngle: 25,
          touch: true,
          loop: false,
          rewind: false,
          responsive: {
            540: {
              items: 6
            },
            640: {
              items: 5
            },
            992: {
              items: 6
            },
            1200: {
              items: 5
            }
          },
          onInit: function(slider){
            cardRadiusCarousel('#carousel-product-block-nav');
          }
        });
        thumbs.events.on('indexChanged',function(){
          cardRadiusCarousel('#carousel-product-block-nav');
        });
        thumbs.events.on('newBreakpointEnd',function(){
          cardRadiusCarousel('#carousel-product-block-nav');
        });
      }
    });
  });

  $(document).on('change', '.form-block__checkbox input[required]', function(){
    var flag = $(this)
    var button = flag.parents('form').find('[type="submit"]');
    if (flag.is(":checked")) {
      button.removeAttr('disabled')      
    } else {
      button.attr('disabled', 'disabled')
    }
  });
});


$(document).on('click','.catalog-card__link-all',function(e){
  e.preventDefault();
  $(this).parents('.catalog-card').toggleClass('is-active');
});
$(document).on('click',function(e){
  if(missClick('.catalog-card',e)){
    $('.catalog-card.is-active').removeClass('is-active');
  }
});

var regTel = /^(\+7|7|8)?[\s\-]?\(?[0-9][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/
var reEmail = /^[a-zA-Z0-9_'+*/^&=?~{}\-](\.?[a-zA-Z0-9_'+*/^&=?~{}\-])*\@((\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}(\:\d{1,3})?)|(((([a-zA-Z0-9][a-zA-Z0-9\-]+[a-zA-Z0-9])|([a-zA-Z0-9]{1,2}))[\.]{1})+([a-zA-Z]{2,6})))$/;

function checkEmail(form) {
    var inputEmail =  form.find('.js-form-email');
    var valueEmail = inputEmail.val();
    var isValidEmail = reEmail.test(valueEmail);

    if(!isValidEmail||valueEmail.length<3) {
        inputEmail.addClass('is-error')
    } else {
        inputEmail.removeClass('is-error')
    }
}

function checkTel(form) {
    var inputTel =  form.find('.js-mask-tel');
    var isValidTel = regTel.test(inputTel.val());
    if(isValidTel) {
        inputTel.removeClass('is-error')
    } else {
        inputTel.addClass('is-error')
    }
}

function checkInputs(form) {
    var inputs = form.find('input[name="name"], input[name="password"], input[name="confirm_password"], input[name="login"]');


    inputs.each(function(i,el) {
        if(!el.checkValidity()) {
            el.classList.add('is-error');
        } else {
            el.classList.remove('is-error');
        }
    })
}

function formValidation(){
  $('.js-validation').each(function(i,el){
    let form = $(this).closest('form');
      $(el).on('keyup', function(e){
          $('.errors-form').remove();
          if($(this).hasClass('js-form-email')) {
             checkEmail(form);
          } else {
              if($(this).get(0).checkValidity()) {
                  $(this).removeClass('is-error')
              } else {
                  $(this).addClass('is-error')
              }
          }
          checkTel(form);
      }
    )
  });


    function validateForm(form) {
      checkEmail(form);
      checkTel(form);
      checkInputs(form);
    }

    $('.js-form-validation').on('submit', function(e) {
        let form = $(this);
        let dataLayerEvent = $(this).data('layer')
        e.preventDefault();
        validateForm(form);
        if(dataLayerEvent !== undefined && dataLayerEvent !== false) {
            dataLayer.push({'event': dataLayerEvent});
        }
    })
}

function fileSet(input){
  let value = $(input).val();
  let block = $(input).parents('.form-block');
  let nameBlock = block.find('.form-block__file-value');
  if (value){
    block.addClass('is-selected');
    nameBlock.html(input.files[0].name);
  }else{
    block.removeClass('is-selected');
  }
}

function resetFile(btn){
  let block = $(btn).parents('.form-block');
  let input = block.find('input[type="file"]');
  input.val('').trigger('change');
}

$(document).on('click','.js-psw',function(){
  let btn = $(this);
  
  if(btn.hasClass('is-active')){
    btn.parent().find('input').attr('type','password');
  }else{
    btn.parent().find('input').attr('type','text');
  }
  btn.toggleClass('is-active');
});

$(window).ready(function(){
  
  let header = $('#header');

  $(window).scroll(function(){

    if($(window).scrollTop() > 150){
      header.addClass('is-sticky');
    }else{
      header.removeClass('is-sticky');
    }
  }).scroll();
  
});



function initSelect2(){

  $('.js-select-default').each(function(i,el) {
    let dropWrap = $('<div class="select__dropdown-wrapper"></div>');
    let select = $(el);
    select.after(dropWrap);
    select.select2({
      language: 'ru',
      minimumResultsForSearch: Infinity,
      width: '100%',
      dropdownParent: dropWrap
    });
  });
}

$(document).ready(function(){
  $('.js-spin-count').TouchSpin({
    buttondown_class: "spin__btn spin__btn--down",
    buttondown_txt: '<svg class="icon icon-minus">\n' +
      '                                                    <use' +
        ' xlink:href="/local/templates/disi/assets/icons/symbol-defs.svg#icon-minus"></use>\n' +
      '                                                </svg>',
    buttonup_class: "spin__btn spin__btn--up",
    buttonup_txt: '<svg class="icon icon-plus">\n' +
      '                                                    <use xlink:href="/local/templates/disi/assets/icons/symbol-defs.svg#icon-plus"></use>\n' +
      '                                                </svg>'
  });
});

