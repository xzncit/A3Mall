
/**
 * 手机验证
 */
export function checkPhone(value){
	if(/^1\d{10}$/.test(value)){
		return true;
	}
	return false
}

/**
 * 身份证验证
 */
export function checkIdCard(value){
	let reg =/^\d{15}|\d{18}$/
	if(reg.test(value)){
		return true;
	}
	return false
}
/**
 * 银行卡验证
 */
export function checkBankCard(value){
	let reg =/^([1-9]{1})(\d{14}|\d{15}|\d{16}|\d{18})$/
	if(reg.test(value)){
		return true;
	}
	return false
}

