routes = [
  {
    path: '/',
    url: './index.html',
  },
  {
    path: '/startpage/',
    componentUrl: './pages/startpage.html',
  }, 
  {
    path: '/product-detail/',
    componentUrl: './pages/product-detail.html',
  }, 
  {
    path: '/my-cart/',
    componentUrl: './pages/my-cart.html',
  },
   {
    path: '/search/',
    componentUrl: './pages/search.html',
  }, 
  {
    path: '/categories/',
    componentUrl: './pages/categories.html',
  },
  {
    path: '/account/',
    componentUrl: './pages/account.html',
  }, 
  {
    path: '/login/',
    componentUrl: './pages/login.html',
  }, 
  {
    path: '/about/',
    componentUrl: './pages/about.html',
  }, 
  {
    path: '/register/',
    componentUrl: './pages/register.html',
  }, 
  {
    path: '/shipping/',
    componentUrl: './pages/shipping.html',
  }, 
  {
    path: '/billing/',
    componentUrl: './pages/billing.html',
  }, 
  {
    path: '/shipping2/',
    componentUrl: './pages/shipping2.html',
  }, 
  {
    path: '/order/',
    componentUrl: './pages/order.html',
  }, 
  {
    path: '/thankyou/',
    componentUrl: './pages/thankyou.html',
  }, 
	
  // Default route (404 page). MUST BE THE LAST
  {
    path: '(.*)',
    url: './pages/404.html',
  },
];
