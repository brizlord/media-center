import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ProxyModal } from './proxy-modal.component';

describe('AdminComponent', () => {
  let component: ProxyModal;
  let fixture: ComponentFixture<ProxyModal>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ProxyModal ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ProxyModal);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
