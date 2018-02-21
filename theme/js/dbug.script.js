$(document).ready(function(){
	$("#add_answer").click(function(){
		fun_add_answer();
	});
	$("#question_type").change(function(){
		fun_question_type();
	});
	fun_add_answer	= function(value)
	{
		val_text	= value || "";
		/* input_text	= $("<input>").attr({type: "text", name: "answer[]", value: val_text});
		input_button	= $("<input>").attr({type: "button", value: "remove", onclick: "$(this).closest('tr').remove();"});
		td	= $("<td/>").append(input_text).append(input_button);
		row	= $("<tr/>", {class: "answer option"}).append($("<td/>")).append(td); */
		row	= $("<div/>", {class: "form_fields answer"}).append($("<input>").attr({type: "text", name: "answer[]", value: val_text})).append($("<input>").attr({class: "answer_button", type: "button", value: "Remove", onclick: "$(this).closest('.form_fields.answer').remove();"}));
		/* row	= $("<div/>", {class: "form_fields answer"}).append($("<input>").attr({type: "text", name: "answer[]", value: val_text})).append($("<button/>", {class: "answer_button", text: "Remove", onclick: "$(this).closest('.form_fields.answer').remove();"})); */
		$(".form_fields.last").before(row);
	};
	fun_question_type	= function()
	{
		val	= $("#question_type").val();
		if(val=="text")
		{
			$(".answer").hide();
		} else {
			$(".answer").show();
		}
	};
});