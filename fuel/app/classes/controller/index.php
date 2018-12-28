<?php
class Controller_Index extends Controller
{
	public function action_example() {
		return View::forge('example');
	}
}