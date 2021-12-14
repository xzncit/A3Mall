import storage from './storage';

export function setSpreadUsers(options){
	if(options == null || options == undefined){
	  return ;
	}

	if((options.u == null || options.u == undefined) || options.u <= 0){
	  return ;
	}
	
	storage.set('spread_id',options.u);
}