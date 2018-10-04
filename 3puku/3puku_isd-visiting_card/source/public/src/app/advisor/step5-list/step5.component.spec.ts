/* tslint:disable:no-unused-variable */
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { DebugElement } from '@angular/core';

import { AdvisorStep1Component } from './advisor-step1.component';

describe('AdvisorStep1Component', () => {
  let component: AdvisorStep1Component;
  let fixture: ComponentFixture<AdvisorStep1Component>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AdvisorStep1Component ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AdvisorStep1Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
