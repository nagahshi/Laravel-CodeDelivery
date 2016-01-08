angular.module('starter.controllers')
	.controller('ClientViewProductsCtrl',[
		'$scope','$state','Product','$ionicLoading','cart','$localStorage','$cart',
		function($scope,$state,Product,$ionicLoading,cart,$localStorage,$cart){			
			
			$scope.products = [];
			$ionicLoading.show({
				template:'Caregando....'
			});
			
			Product.query({},function(data){
				$scope.products = data.data;
				$ionicLoading.hide();
			},function(dataError){
				$ionicLoading.hide();
			});
			
			$scope.addItem = function(item){
				item.qtd = 1;
				$cart.addItem(item);
				$state.go('client.checkout');
			};
		}
	]);