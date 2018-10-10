/* tslint:disable:no-unused-variable */
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { DebugElement } from '@angular/core';

import { GuideEditComponent } from './guide-edit.component';

describe('GuideEditComponent', () => {
  let component: GuideEditComponent;
  let fixture: ComponentFixture<GuideEditComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ GuideEditComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(GuideEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
