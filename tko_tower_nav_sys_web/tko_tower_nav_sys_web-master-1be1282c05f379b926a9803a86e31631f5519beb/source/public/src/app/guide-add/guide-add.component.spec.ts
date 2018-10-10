/* tslint:disable:no-unused-variable */
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { DebugElement } from '@angular/core';

import { GuideAddComponent } from './guide-add.component';

describe('GuideAddComponent', () => {
  let component: GuideAddComponent;
  let fixture: ComponentFixture<GuideAddComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ GuideAddComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(GuideAddComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
