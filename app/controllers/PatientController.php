<?php

require_once '../app/models/Patient.php';

class PatientController
{
    public function index()
    {
        echo json_encode(Patient::all());
    }

    public function store()
    {
        Patient::create($GLOBALS['request']['body']);
        echo json_encode(['message'=>'Patient added']);
    }

    public function update($id)
    {
        Patient::update($id, $GLOBALS['request']['body']);
        echo json_encode(['message'=>'Patient updated']);
    }

    public function destroy($id)
    {
        Patient::delete($id);
        echo json_encode(['message'=>'Patient deleted']);
    }
}
