import '../sass/main.scss'
import $ from 'jquery';

// Component handlers
import navbarHandler                    from './components/navbar';
import fullscreenSliderHandler          from './components/fullscreenSlider';
import newsListHandler                  from './components/newsList';
import vimeoPlayerHandler               from './components/vimeoPlayer';
import timelineHandler                  from './components/timeline';
import brandHandler                     from './components/brand';
import portfolioHandler                 from './components/portfolio';
import searchPanelHandler               from './components/search';
import documentsHandler                 from './components/documents';
import textSliderHandler                from './components/textSlider';
import contactHandler                   from './components/contact';
import navigationSideBarTempleateHandler from './components/navigationSideBarTemplate';
import annualReportsHandler             from './components/annualReports';
import ShareQuotationInit               from './components/ShareQuotation';
import eventsHandler                    from './components/events';


// Component init
if($('.navbar-partial-df27ae').length > 0)              navbarHandler( $('.navbar-partial-df27ae') );
if($('.fullscreenslider-partial-09e45b').length > 0)    fullscreenSliderHandler( $('.fullscreenslider-partial-09e45b') );
if($('.newslist-partial-afa6f9').length > 0)            newsListHandler( $('.newslist-partial-afa6f9') );
if($('.vimeoplayer-partial-8e5131').length > 0)         vimeoPlayerHandler( $('.vimeoplayer-partial-8e5131') );
if($('.timeline-partial-04e48b').length > 0)            timelineHandler( $('.timeline-partial-04e48b') );
if($('.brand-partial-e66256').length > 0)               brandHandler( $('.brand-partial-e66256') );
if($('.portfolio-partial-8f3f8b').length > 0)           portfolioHandler( $('.portfolio-partial-8f3f8b') );
if($('.searchpanel-partial-1749fc').length > 0)         searchPanelHandler( $('.searchpanel-partial-1749fc') );
if($('.documents-partial-15af9d').length > 0)           documentsHandler( $('.documents-partial-15af9d') );
if($('.textslider-partial-8bf200').length > 0)          textSliderHandler( $('.textslider-partial-8bf200') );
if($('.contact-partial-555b5f').length > 0)             contactHandler( $('.contact-partial-555b5f') );
if($('#navigationsidebar-template-207713').length > 0)  navigationSideBarTempleateHandler( $('#navigationsidebar-template-207713') );
if($('.annualreports-partial-5d3457').length > 0)       annualReportsHandler( $('.annualreports-partial-5d3457') );
if($('.sharequotation-partial-7baa8d').length > 0)      ShareQuotationInit( $('.sharequotation-partial-7baa8d') );
if($('.events-partial-e5e1bb').length > 0)              eventsHandler( $('.events-partial-e5e1bb') );

// Dark Mode
if($('main > section.dark-style:last-of-type').length == 1) $('main').addClass('latest-block-is-dark');


// console.info(`
// [Hi!. You shouldn't be here, so try some corn]
//         ⡠⡤⢤⣀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀ ⠀⠀⠀
//         ⢿⡢⣁⢄⢫⡲⢤⡀⠀⠀⠀⠀⢀⠄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀ ⠀⠀⠀
//         ⠘⣧⡁⢔⢑⢄⠙⣬⠳⢄⠀⠀⣾⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀ ⠀⠀⠀⠀
//          ⠘⢎⣤⠑⣤⠛⢄⠝⠃⡙⢦⣸⣧⡀⠀⢠⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀ ⠀⠀⠀⠀⠀
//           ⠈⢧⡿⣀⠷⣁⠱⢎⠉⣦⡛⢿⣷⣤⣯⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀ ⠀⠀
//          ⠈⠉⠛⠻⢶⣵⣎⣢⡜⠣⣠⠛⢄⣜⣳⣿⣿⣿⡄⠀⠀⠀⠀⠀⠀⠀⠀⠀ ⠀⠀⠀⠀⠀⠀⠀
//               ⠈⠻⢿⣿⣾⣿⣾⣿⣿⣿⣿⣿⣿⣷⣄⠀⠀⠀⠀⠀⠀⠀⠀ ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                  ⠙⢿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣷⡀⠀⠀⠀⠀⠀⠀ ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                   ⣰⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣷⠀⠀⠀⠀⠀⠀ ⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                  ⢰⣿⠟⠛⠛⠛⢿⣿⣿⣿⣿⣿⣿⣿⣿⡇⠀⠀⠀⠀⠀ ⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                  ⢸⠋⠀⠀⠀⠀⠀⠙⠿⣿⣿⣿⣿⣿⣿⣿⠂⠀⠀⠀⠀ ⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                  ⠈⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠻⠿⠋⠁⠀
// `);